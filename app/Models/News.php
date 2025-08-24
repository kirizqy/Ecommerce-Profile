<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $table = 'news';

    protected $fillable = [
        'title','slug','content','image','status','published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /* ================== Auto-clean file saat delete ================== */
    protected static function booted()
    {
        static::deleting(function (News $news) {
            // hapus file image kalau tersimpan lokal
            $news->deleteFileIfExists($news->image);
        });
    }

    private function deleteFileIfExists(?string $raw): void
    {
        if (!$raw) return;

        // kalau URL eksternal → jangan dihapus
        if (Str::startsWith($raw, ['http://','https://'])) return;

        $path = ltrim(str_replace('\\','/',$raw), '/');
        $path = preg_replace('#^(public/|storage/)#', '', $path);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        $publicPath = public_path($path);
        if ($path && is_file($publicPath)) @unlink($publicPath);
    }

    /* ============ Aliases body <-> content (kompat form lama) ============ */
    public function setBodyAttribute($value) { $this->attributes['content'] = $value; }
    public function getBodyAttribute() { return $this->attributes['content'] ?? null; }

    /* ============ Accessor URL gambar aman ============ */
    public function getImageUrlAttribute(): ?string
    {
        $raw = trim((string) ($this->image ?? ''));
        if ($raw === '') return null;

        if (Str::startsWith($raw, ['http://','https://'])) return $raw;

        $path = ltrim(str_replace('\\','/',$raw), '/');
        $path = preg_replace('#^(public/|storage/)#', '', $path);

        if ($path && Storage::disk('public')->exists($path)) return asset('storage/'.$path);
        if ($path && file_exists(public_path($path)))       return asset($path);

        return null;
    }

    /* ============ Query Scopes ============ */
    public function scopePublished($q)
    {
        return $q->where('status', self::STATUS_PUBLISHED)
                 ->whereNotNull('published_at')
                 ->where('published_at', '<=', now());
    }

    public function scopeDraft($q)
    {
        return $q->where('status', self::STATUS_DRAFT);
    }
}
