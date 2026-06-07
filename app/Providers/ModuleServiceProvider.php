<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Automatically load views from all modules
        if (class_exists(View::class)) {
            foreach (glob(base_path('app/Modules/*/Views'), GLOB_ONLYDIR) as $viewPath) {
                $moduleName = basename(dirname($viewPath));
                View::addNamespace(strtolower($moduleName), $viewPath);
            }
        }
    }
}