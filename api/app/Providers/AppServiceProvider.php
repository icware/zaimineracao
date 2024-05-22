<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Packages\System\Controllers\SystemCodeController;

class AppServiceProvider extends ServiceProvider
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
        //
    }
}
