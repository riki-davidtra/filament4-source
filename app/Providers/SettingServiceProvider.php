<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SettingItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('settingItems', function () {
            if (Schema::hasTable('setting_items')) {
                return SettingItem::all()->keyBy('key');
            }
            return collect();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $settingItems = app('settingItems');
            $view->with('settingItems', $settingItems);
        });
    }
}
