@extends('layouts.app')

@section('content')
<div class="password-reset-container">
    <div class="password-reset-header">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Angelow">
        </div>
        <h1>Recuperar contraseña</h1>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="password-reset-form">
        @csrf

        <div class="form-group">
            <label for="email">Correo electrónico o teléfono</label>
            <input id="email" type="text" class="@error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required 
                   placeholder="Ej: juan@email.com o 3001234567">
            
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn-primary">
            Enviar enlace de recuperación
        </button>
    </form>

    <div class="back-to-login">
        <a href="{{ route('login') }}" class="text-link">
            <i class="fas fa-arrow-left"></i> Volver al login
        </a>
    </div>
</div>
@endsection