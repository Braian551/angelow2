@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-header">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Angelow">
        </div>
        <h1>Iniciar sesión</h1>
    </div>

    <!-- Barra de progreso -->
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

    <!-- Formulario por pasos -->
    <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm" novalidate>
        @csrf
        
        <!-- Paso 1: Correo/Teléfono -->
        <div class="form-step active" data-step="1">
            <div class="form-group">
                <label for="username">Correo electrónico o teléfono</label>
                <input type="text" id="correo_login" name="email" placeholder="Ej: juan@email.com o 3001234567" required>
                <div class="form-hint">Ingresa el correo o teléfono con el que te registraste</div>
            </div>
            <button type="button" class="btn-primary next-step">Continuar</button>
        </div>

        <!-- Paso 2: Contraseña -->
        <div class="form-step" data-step="2">
            <div class="form-group password-group">
                <label for="password">Contraseña</label>
                <div class="password-input-container">
                    <input type="password" id="loginPassword" name="password" placeholder="Ingresa tu contraseña" required>
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="form-hint">La contraseña distingue entre mayúsculas y minúsculas</div>
            </div>
            
            <div class="login-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordar mi cuenta</label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </div>
            
            <div class="step-buttons">
                <button type="button" class="btn-outline prev-step">Atrás</button>
                <button type="submit" class="btn-primary" name="submit2">Iniciar sesión</button>
            </div>
        </div>
    </form>

    <div class="social-login">
        <p>También puedes iniciar sesión con:</p>
        <div class="social-buttons">
            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="social-btn google">
                <i class="fab fa-google"></i> Google
            </a>
        </div>
    </div>

    <div class="register-redirect">
        ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-link">Regístrate</a>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/validacionlogin.js') }}"></script>
    <script src="{{ asset('js/alerta.js') }}"></script>
@endpush