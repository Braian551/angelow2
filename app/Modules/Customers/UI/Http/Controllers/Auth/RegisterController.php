<?php

namespace App\Modules\Customers\UI\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeCustomerMail;
use App\Models\User;
use App\Modules\Customers\UI\Http\Requests\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('customers.dashboard');
        }

        return view('modules.customers.auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data): User {
            return User::create([
            'id' => uniqid('', false),
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'identification_type' => 'cc',
            ]);
        });

        Auth::login($user);
        session()->regenerate();

        try {
            Mail::to($user->email)->send(new WelcomeCustomerMail($user));
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar el correo de bienvenida', [
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()
            ->route('customers.dashboard')
            ->with('status', 'Tu cuenta se creó correctamente. ¡Bienvenido a Angelow!');
    }
}
