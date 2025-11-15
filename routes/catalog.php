<?php

use App\Modules\Catalog\UI\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::name('catalog.')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');
        // Otros endpoints del catálogo irán aquí
    });
