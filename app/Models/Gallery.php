<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'description',
        // legacy:
        'image',
        // baru:
        'media',   // path file image/video di storage/public
        'type',    // 'image' | 'video' (nullable → auto dari mime)
        'poster',  // thumbnail video (opsional)
    ];

    /* ================== Auto-clean file saat delete ================== */
    protected static function booted()
    {
        static::deleting(function (Gallery $gallery) {
            $gallery->deleteFileIfExists($gallery->media);
            $gallery->deleteFileIfExists($gallery->image);
            $gallery->deleteFileIfExists($gallery->poster);
        });
    }

    private function deleteFileIfExists(?string $raw): void
    {
        if (!$raw) return;

        // kalau URL eksternal jangan dihapus
        if (Str::startsWith($raw, ['http://','https://'])) return;

        $path = ltrim(str_replace('\\','/',$raw), '/');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        $publicPath = public_path($path);
        if (is_file($publicPath)) @unlink($publicPath);
    }

    /* ================== Accessors ================== */
    public function getUrlAttribute(): string
    {
        $value = $this->media ?? $this->image ?? null;
        return $value
            ? asset('storage/' . ltrim($value, '/'))
            : asset('images/placeholder.webp');
    }

    public function getPosterUrlAttribute(): string
    {
        return $this->poster
            ? asset('storage/' . ltrim($this->poster, '/'))
            : asset('images/placeholder.webp');
    }

    public function getIsVideoAttribute(): bool
    {
        if (!empty($this->type)) return strtolower($this->type) === 'video';
        $path = strtolower(($this->media ?? $this->image ?? ''));
        return str_ends_with($path, '.mp4')
            || str_ends_with($path, '.webm')
            || str_ends_with($path, '.ogg')
            || str_ends_with($path, '.ogv')
            || str_ends_with($path, '.mov')
            || str_ends_with($path, '.m4v');
    }

    public function getIsImageAttribute(): bool
    {
        return !$this->is_video;
    }

    public function getMimeTypeAttribute(): string
    {
        $path = ($this->media ?? $this->image ?? '') ?: '';
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $mapImg = [
            'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png',
            'webp'=>'image/webp','gif'=>'image/gif','bmp'=>'image/bmp','svg'=>'image/svg+xml',
        ];
        $mapVid = [
            'mp4'=>'video/mp4','m4v'=>'video/x-m4v','webm'=>'video/webm',
            'ogg'=>'video/ogg','ogv'=>'video/ogg','mov'=>'video/quicktime',
        ];

        if (isset($mapImg[$ext])) return $mapImg[$ext];
        if (isset($mapVid[$ext])) return $mapVid[$ext];
        if ($this->is_video) return 'video/*';
        if ($ext) return 'image/*';
        return 'application/octet-stream';
    }
}
