@extends('layouts.auth')

@section('title', 'Iniciar sesión - Angelow')

@push('styles')
    @vite('resources/css/modules/customers/index.css')
@endpush

@push('scripts')
    @vite('resources/js/modules/customers/index.js')
@endpush

@section('content')
    <div class="login-container" id="login-container">
        <div class="login-header">
            <div class="login-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Angelow">
            </div>
            <h1>Iniciar sesión</h1>
        </div>

        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Correo/Teléfono</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-title">Contraseña</div>
            </div>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('customers.login.store') }}" class="login-form" id="loginForm" novalidate>
            @csrf
            <div class="form-step active" data-step="1">
                <div class="form-group">
                    <label for="correo_login">Correo electrónico o teléfono</label>
                    <input
                        type="text"
                        id="correo_login"
                        name="identifier"
                        placeholder="Ej: juan@email.com o 3001234567"
                        value="{{ old('identifier') }}"
                        required
                    >
                    <div class="form-hint">Ingresa el correo o teléfono con el que te registraste</div>
                    @error('identifier')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="button" class="btn-primary next-step">Continuar</button>
            </div>

            <div class="form-step" data-step="2">
                <div class="form-group password-group">
                    <label for="loginPassword">Contraseña</label>
                    <div class="password-input-container">
                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            placeholder="Ingresa tu contraseña"
                            required
                        >
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-hint">La contraseña distingue entre mayúsculas y minúsculas</div>
                    @error('password')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="login-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Recordar mi cuenta</label>
                    </div>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="step-buttons">
                    <button type="button" class="btn-outline prev-step">Atrás</button>
                    <button type="submit" class="btn-primary">Iniciar sesión</button>
                </div>
            </div>
        </form>

        <div class="social-login">
            <p>También puedes iniciar sesión con:</p>
            <div class="social-buttons">
                <a href="#" class="social-btn google" aria-disabled="true">
                    <i class="fab fa-google"></i> Próximamente
                </a>
            </div>
        </div>

        <div class="register-redirect">
            ¿No tienes una cuenta?
            <a href="{{ route('customers.register') }}" class="text-link">Regístrate</a>
        </div>
    </div>

    @php($firstError = session('errors')?->first())
    @if ($firstError)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.showAlert) {
                    window.showAlert(<?php echo json_encode($firstError); ?>, 'error');
                }
            });
        </script>
    @endif

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.showAlert) {
                    window.showAlert(<?php echo json_encode(session('status')); ?>, 'success');
                }
            });
        </script>
    @endif
@endsection
