<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasPublicUuid;

class Setting extends Model
{
    use HasFactory, HasPublicUuid;

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
