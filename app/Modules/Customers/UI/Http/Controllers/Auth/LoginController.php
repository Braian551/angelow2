<?php

namespace App\Modules\Customers\UI\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Customers\Application\Services\RememberTokenService;
use App\Modules\Customers\UI\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct(private readonly RememberTokenService $rememberTokens)
    {
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('customers.dashboard');
        }

        $rememberCookie = $request->cookie(RememberTokenService::COOKIE_NAME);
        $rememberedUser = $this->rememberTokens->resolveFromCookie($rememberCookie);

        if ($rememberedUser) {
            Auth::login($rememberedUser, true);
            $this->updateLastAccess($rememberedUser);
            session()->regenerate();
            $this->rememberTokens->issue($rememberedUser, $request);

            return redirect()->intended(route('customers.dashboard'));
        }

        if ($rememberCookie) {
            $this->rememberTokens->forget();
        }

        return view('modules.customers.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $identifier = trim((string) $request->validated('identifier'));
        $password = (string) $request->validated('password');
        $remember = (bool) $request->boolean('remember');

        $user = $this->findUserByIdentifier($identifier);

        if (! $user) {
            $this->registerFailedAttempt($identifier, $request);
            $this->throwInvalidCredentials();
        }

        if ($user->is_blocked) {
            throw ValidationException::withMessages([
                'identifier' => 'Tu cuenta ha sido bloqueada. Por favor contacta al equipo de soporte.',
            ]);
        }

        if (! $user->password || ! Hash::check($password, $user->password)) {
            $this->registerFailedAttempt($identifier, $request);
            $this->throwInvalidCredentials();
        }

        Auth::login($user, false);
        session()->regenerate();
        $this->updateLastAccess($user);

        if ($remember) {
            $this->rememberTokens->issue($user, $request);
        } else {
            $this->rememberTokens->forget($user);
        }

        return redirect()->intended(route('customers.dashboard'))
            ->with('status', 'Bienvenido de nuevo, '.$user->name.' 👋');
    }

    private function findUserByIdentifier(string $identifier): ?User
    {
        $query = User::query();

        if ($this->isEmail($identifier)) {
            $query->where('email', strtolower($identifier));
        } else {
            $query->where('phone', $identifier);
        }

        return $query->first();
    }

    private function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function registerFailedAttempt(string $identifier, Request $request): void
    {
        DB::table('login_attempts')->insert([
            'username' => $identifier,
            'ip_address' => $request->ip(),
            'attempt_date' => Carbon::now(),
        ]);

        $recentAttempts = DB::table('login_attempts')
            ->where(function ($query) use ($identifier, $request): void {
                $query->where('username', $identifier)
                    ->orWhere('ip_address', $request->ip());
            })
            ->where('attempt_date', '>', Carbon::now()->subHour())
            ->count();

        if ($recentAttempts >= 5) {
            DB::table('users')
                ->where('email', $identifier)
                ->orWhere('phone', $identifier)
                ->update(['is_blocked' => 1]);
        }
    }

    private function updateLastAccess(User $user): void
    {
        $user->forceFill(['last_access' => Carbon::now()])->save();
    }

    /**
     * @return never
     * @throws ValidationException
     */
    private function throwInvalidCredentials(): never
    {
        throw ValidationException::withMessages([
            'identifier' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }
}
