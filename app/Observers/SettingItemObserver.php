<?php

namespace App\Observers;

use App\Models\SettingItem;
use Illuminate\Support\Facades\Storage;

class SettingItemObserver
{
    public function updating(SettingItem $settingItem): void
    {
        if ($settingItem->isDirty('value') && $settingItem->type === 'file') {
            $originalValue = $settingItem->getOriginal('value');

            if ($originalValue && Storage::disk('public')->exists($originalValue)) {
                Storage::disk('public')->delete($originalValue);
            }
        }
    }

    public function deleting(SettingItem $settingItem): void
    {
        if ($settingItem->type === 'file' && $settingItem->value) {
            if (Storage::disk('public')->exists($settingItem->value)) {
                Storage::disk('public')->delete($settingItem->value);
            }
        }
    }
}
