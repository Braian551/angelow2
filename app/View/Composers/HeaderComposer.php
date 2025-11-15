<?php

namespace App\View\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HeaderComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'headerCartCount' => $this->resolveCartCount(),
            'headerSearchTerm' => request('search', ''),
        ]);
    }

    private function resolveCartCount(): int
    {
        try {
            $userId = Auth::id();
            $sessionId = session()->getId();

            $cartQuery = DB::table('carts');

            if ($userId) {
                $cartQuery->where('user_id', $userId);
            } else {
                $cartQuery->where('session_id', $sessionId)->whereNull('user_id');
            }

            $cartId = $cartQuery->orderByDesc('created_at')->value('id');

            if (! $cartId) {
                return 0;
            }

            return (int) DB::table('cart_items')
                ->where('cart_id', $cartId)
                ->sum('quantity');
        } catch (\Throwable $th) {
            report($th);
            return 0;
        }
    }
}
