<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $modules = config('modules.modules', []);

        foreach ($modules as $module) {
            if (! empty($module['provider'])) {
                $this->app->register($module['provider']);
            }
        }
    }

    public function boot(): void
    {
        $modules = config('modules.modules', []);

        foreach ($modules as $module) {
            $routesPath = base_path($module['routes'] ?? '');

            if ($routesPath && File::exists($routesPath)) {
                Route::middleware('web')->group($routesPath);
            }
        }
    }
}
