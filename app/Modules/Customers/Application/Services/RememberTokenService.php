<?php

namespace App\Modules\Customers\Application\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class RememberTokenService
{
    public const COOKIE_NAME = 'remember_me';
    private const LIFETIME_DAYS = 30;

    public function issue(User $user, Request $request): void
    {
        $token = Str::random(64);
        $expiry = Carbon::now()->addDays(self::LIFETIME_DAYS);

        $user->forceFill([
            'remember_token' => $token,
            'token_expiry' => $expiry,
        ])->save();

        Cookie::queue(cookie(
            self::COOKIE_NAME,
            $token,
            $expiry->diffInMinutes(),
            '/',
            config('session.domain'),
            (bool) ($request->isSecure() || config('session.secure')),
            true,
            false,
            config('session.same_site', 'lax')
        ));
    }

    public function forget(?User $user = null): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE_NAME));

        if ($user) {
            $user->forceFill([
                'remember_token' => null,
                'token_expiry' => null,
            ])->save();
        }
    }

    public function resolveFromCookie(?string $token): ?User
    {
        if (empty($token)) {
            return null;
        }

        return User::query()
            ->where('remember_token', $token)
            ->where('token_expiry', '>', Carbon::now())
            ->first();
    }
}
