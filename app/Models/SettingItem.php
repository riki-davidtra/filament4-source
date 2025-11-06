<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingItem extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'value'   => 'array',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid7();
    }

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
