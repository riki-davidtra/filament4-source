<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Setting extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function settingItems()
    {
        return $this->hasMany(SettingItem::class);
    }
}
