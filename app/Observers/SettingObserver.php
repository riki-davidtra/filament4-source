<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    public function creating(Setting $setting): void
    {
        if (!$setting->order) {
            $setting->order = Setting::max('order') + 1;
        }
    }

    public function deleting(Setting $setting): void
    {
        $setting->settingItems()->delete();
    }
}
