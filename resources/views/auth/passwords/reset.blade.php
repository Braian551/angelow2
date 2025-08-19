@extends('layouts.app')

@section('content')
<div class="password-reset-container">
    <div class="password-reset-header">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Angelow">
        </div>
        <h1>Restablecer contraseña</h1>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="password-reset-form">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" class="@error('email') is-invalid @enderror" 
                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
            
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Nueva contraseña</label>
            <input id="password" type="password" class="@error('password') is-invalid @enderror" 
                   name="password" required autocomplete="new-password">
            
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmar nueva contraseña</label>
            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-primary">
            Restablecer contraseña
        </button>
    </form>
</div>
@endsection