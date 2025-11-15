<?php

namespace App\Modules\Catalog\UI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $now = Carbon::now();

        $topBarAnnouncement = $this->resolveAnnouncement('top_bar', $now);
        $promoBanner = $this->resolveAnnouncement('promo_banner', $now);

        $sliders = Schema::hasTable('sliders')
            ? DB::table('sliders')
                ->where('is_active', 1)
                ->orderBy('order_position')
                ->get()
            : collect();

        $categories = Schema::hasTable('categories')
            ? DB::table('categories')
                ->where('is_active', 1)
                ->whereNull('parent_id')
                ->orderByDesc('created_at')
                ->limit(4)
                ->get()
            : collect();

        $products = $this->resolveFeaturedProducts();

        $collections = Schema::hasTable('collections')
            ? DB::table('collections')
                ->where('is_active', 1)
                ->orderByDesc('launch_date')
                ->limit(3)
                ->get()
            : collect();

        return view('modules.catalog.home', [
            'topBarAnnouncement' => $topBarAnnouncement,
            'promoBanner' => $promoBanner,
            'sliders' => $sliders,
            'categories' => $categories,
            'products' => $products,
            'collections' => $collections,
        ]);
    }

    private function resolveAnnouncement(string $type, Carbon $now): ?object
    {
        if (! Schema::hasTable('announcements')) {
            return null;
        }

        return DB::table('announcements')
            ->where('type', $type)
            ->where('is_active', 1)
            ->where(function ($query) use ($now): void {
                $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($query) use ($now): void {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * @return array<int, object>
     */
    private function resolveFeaturedProducts(): array
    {
        $requiredTables = collect(['products', 'product_images', 'categories']);

        if ($requiredTables->contains(fn (string $table): bool => ! Schema::hasTable($table))) {
            return [];
        }

        return DB::select(
            <<<SQL
            SELECT
                p.id,
                p.name,
                p.slug,
                p.price,
                p.compare_price,
                p.gender,
                p.is_featured,
                c.name as category_name,
                (
                    SELECT image_path 
                    FROM product_images 
                    WHERE product_id = p.id 
                    ORDER BY is_primary DESC, `order` ASC 
                    LIMIT 1
                ) as main_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT 6
            SQL
        );
    }
}
