<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (!app()->environment('local')) {
            URL::forceScheme('https');
        }

        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        \App\Models\SettingItem::observe(\App\Observers\SettingItemObserver::class);
    }
}
