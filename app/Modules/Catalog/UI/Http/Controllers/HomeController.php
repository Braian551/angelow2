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
                ->map(function ($slider) {
                    $slider->image_path = $this->normalizeLegacyPath($slider->image_path ?? $slider->image ?? null);
                    return $slider;
                })
            : collect();

        $categories = Schema::hasTable('categories')
            ? DB::table('categories')
                ->where('is_active', 1)
                ->whereNull('parent_id')
                ->orderByDesc('created_at')
                ->limit(4)
                ->get()
                ->map(function ($category) {
                    $category->image_path = $this->normalizeLegacyPath($category->image ?? $category->banner ?? null);
                    return $category;
                })
            : collect();

        $products = $this->resolveFeaturedProducts();

        $collections = Schema::hasTable('collections')
            ? DB::table('collections')
                ->where('is_active', 1)
                ->orderByDesc('launch_date')
                ->limit(3)
                ->get()
                ->map(function ($collection) {
                    $collection->image_path = $this->normalizeLegacyPath($collection->image ?? null);
                    return $collection;
                })
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

        $products = DB::select(
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

        // Normalize paths returned by legacy DB to use the public uploads path
        foreach ($products as $idx => $product) {
            $products[$idx]->main_image = $this->normalizeLegacyPath($product->main_image ?? null);
        }

        return $products;
    }

    private function normalizeLegacyPath(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path));
        if ($path === '') {
            return null;
        }

        $baseUrl = rtrim(url('/'), '/');
        if (str_starts_with($path, $baseUrl)) {
            $path = ltrim(substr($path, strlen($baseUrl)), '/');
        }

        $leadingSegments = [
            'public/',
            'public_html/',
            'storage/app/public/',
            'public/storage/',
        ];

        foreach ($leadingSegments as $segment) {
            if (str_starts_with($path, $segment)) {
                $path = substr($path, strlen($segment));
                break;
            }
        }

        if (str_starts_with($path, 'uploads/legacy/')) {
            $path = preg_replace('/^uploads\/legacy\//', 'uploads/', $path);
        }

        $normalizedPath = ltrim($path, '/');
        if (! file_exists(public_path($normalizedPath))) {
            $storagePath = 'storage/' . $normalizedPath;
            if (file_exists(public_path($storagePath))) {
                $normalizedPath = $storagePath;
            }
        }

        return $normalizedPath;
    }
}
