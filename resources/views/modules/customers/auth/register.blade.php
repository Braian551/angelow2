@extends('layouts.auth')

@section('title', 'Crear cuenta - Angelow')

@push('styles')
    @vite('resources/css/modules/customers/index.css')
@endpush

@push('scripts')
    @vite('resources/js/modules/customers/index.js')
@endpush

@section('content')
    <div class="register-container" id="register-container">
        <div class="register-header">
            <div class="register-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Angelow">
            </div>
            <h1>Crea tu cuenta</h1>
        </div>

        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Nombre</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-title">Correo</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-title">Teléfono</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-title">Contraseña</div>
            </div>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('customers.register.store') }}" class="register-form" id="registerForm" novalidate>
            @csrf
            <div class="form-step active" data-step="1">
                <div class="form-group">
                    <label for="username">Nombre completo</label>
                    <input type="text" id="username" name="name" placeholder="Ej: Juan Pérez" value="{{ old('name') }}" required>
                    <div class="form-hint">Así aparecerás en Angelow</div>
                    @error('name')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="button" class="btn-primary next-step">Continuar</button>
            </div>

            <div class="form-step" data-step="2">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ej: juan@email.com" value="{{ old('email') }}" required>
                    <div class="form-hint">Usaremos este correo para contactarte</div>
                    @error('email')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="step-buttons">
                    <button type="button" class="btn-outline prev-step">Atrás</button>
                    <button type="button" class="btn-primary next-step">Continuar</button>
                </div>
            </div>

            <div class="form-step" data-step="3">
                <div class="form-group">
                    <label for="phone">Teléfono (opcional)</label>
                    <input type="tel" id="phone" name="phone" placeholder="Ej: 3001234567" value="{{ old('phone') }}">
                    <div class="form-hint">Podrás usarlo para iniciar sesión</div>
                    @error('phone')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="step-buttons">
                    <button type="button" class="btn-outline prev-step">Atrás</button>
                    <button type="button" class="btn-primary next-step">Continuar</button>
                </div>
            </div>

            <div class="form-step" data-step="4">
                <div class="form-group password-group">
                    <label for="password">Contraseña</label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" placeholder="Crea tu contraseña" required>
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-hint">Debe tener al menos 6 caracteres</div>
                    <div id="password-strength-bar"></div>
                    @error('password')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="terms-container">
                    <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                    <label for="terms">
                        Acepto los <a href="{{ url('/informacion/terminos') }}" target="_blank">Términos y condiciones</a>
                        y las <a href="{{ url('/informacion/privacidad') }}" target="_blank">Políticas de privacidad</a> de Angelow
                    </label>
                    @error('terms')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="step-buttons">
                    <button type="button" class="btn-outline prev-step">Atrás</button>
                    <button type="submit" class="btn-primary">Crear cuenta</button>
                </div>
            </div>
        </form>

        <div class="social-login">
            <p>También puedes registrarte con:</p>
            <div class="social-buttons">
                <a href="#" class="social-btn google" aria-disabled="true">
                    <i class="fab fa-google"></i> Próximamente
                </a>
            </div>
        </div>

        <div class="login-redirect">
            ¿Ya tienes una cuenta?
            <a href="{{ route('login') }}" class="text-link">Inicia sesión</a>
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
