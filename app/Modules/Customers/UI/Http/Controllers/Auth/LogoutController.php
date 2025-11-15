<?php

namespace App\Modules\Customers\UI\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Customers\Application\Services\RememberTokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __construct(private readonly RememberTokenService $rememberTokens)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->rememberTokens->forget($user);

        return redirect()->route('login')->with('status', 'Has cerrado sesión correctamente.');
    }
}
