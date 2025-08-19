<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo_login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determinar si es email o teléfono
        $field = filter_var($request->correo_login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([
            $field => $request->correo_login,
            'password' => $request->password
        ], $request->remember)) {
            throw ValidationException::withMessages([
                'correo_login' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }
}