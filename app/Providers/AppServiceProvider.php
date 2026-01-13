<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        // Share settings globally with all views
        View::composer('*', function ($view) {
            $settings = Setting::latest()->first();
            // Add helper methods for easier access
            if ($settings) {
                $settings->app_name = $settings->app_name ?? config('app.name', 'Tour Management');
            } else {
                // Create a dummy object with defaults if no settings exist
                $settings = (object) [
                    'app_name' => config('app.name', 'Tour Management'),
                    'app_slogan' => null,
                    'app_logo_url' => asset('frontend/logo/logo.png'),
                    'app_background_image_url' => null,
                ];
            }
            $view->with('settings', $settings);
        });
    }
}
