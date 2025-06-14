<?php

namespace App\Providers;

use Midtrans\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // 1. PASTIKAN USE STATEMENT INI ADA

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
