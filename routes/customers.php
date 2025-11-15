<?php

use App\Modules\Customers\UI\Http\Controllers\Auth\LoginController;
use App\Modules\Customers\UI\Http\Controllers\Auth\LogoutController;
use App\Modules\Customers\UI\Http\Controllers\Auth\RegisterController;
use App\Modules\Customers\UI\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('customers.login');
    Route::post('/login', [LoginController::class, 'store'])->name('customers.login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('customers.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('customers.register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/mi-cuenta', DashboardController::class)->name('customers.dashboard');
    Route::post('/logout', LogoutController::class)->name('customers.logout');
});
