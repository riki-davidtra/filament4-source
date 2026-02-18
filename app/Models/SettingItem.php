<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasPublicUuid;

class SettingItem extends Model
{
    use HasFactory, HasPublicUuid;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'value'   => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }

    protected static function isPathFile($path): bool
    {
        return is_string($path) && preg_match('/\.(jpg|jpeg|png|gif|webp|svg|bmp|ico|tiff|psd|ai|eps|heic|pdf|doc|docx|xls|xlsx|ppt|pptx|txt|csv|json|xml|mp4|mov|mkv|avi|wmv|webm|mp3|wav|ogg|flac|zip|rar|7z|tar|gz|apk|exe|msi)$/i', $path);
    }
}
