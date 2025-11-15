<?php

use Illuminate\Support\Facades\Route;

Route::prefix('backoffice')
    ->name('backoffice.')
    ->middleware(['auth'])
    ->group(function (): void {
        // TODO: add backoffice routes
    });
