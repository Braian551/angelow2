<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Recuperación de contraseña
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
         ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
         ->name('password.email');
    
    // Reset de contraseña
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
         ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
         ->name('password.update');
    
    // Login social
    Route::get('/auth/{provider}', [SocialLoginController::class, 'redirectToProvider'])
         ->name('social.login');
    Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);
});

// Ruta de logout (solo para usuarios autenticados)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');