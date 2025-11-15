<?php

namespace App\Modules\Customers\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class CustomersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind customer services here
    }

    public function boot(): void
    {
        // Bootstrapping logic for customers module
    }
}
