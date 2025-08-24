<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Slider extends Model
{
    protected $fillable = ['title', 'image', 'description', 'status', 'order'];

    protected $casts = [
        'status' => 'boolean',
        'order'  => 'integer',
    ];

    // Biar saat di-serialize/JSON, field image_url ikut muncul
    protected $appends = ['image_url'];

    /* ================== Auto-clean file saat delete ================== */
    protected static function booted()
    {
        static::deleting(function (Slider $slider) {
            $slider->deleteFileIfExists($slider->image);
        });
    }

    private function deleteFileIfExists(?string $raw): void
    {
        if (!$raw) return;

        // skip kalau URL eksternal
        if (Str::startsWith($raw, ['http://','https://','//'])) return;

        $path = $this->normalizePath($raw);

        // Coba di disk public
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        // Fallback: file di public/
        $publicPath = public_path($path);
        if ($path && is_file($publicPath)) @unlink($publicPath);
    }

    private function normalizePath(string $raw): string
    {
        $raw  = str_replace('\\','/',$raw);
        // buang prefix umum
        $path = preg_replace('#^(public/|storage/)#', '', ltrim($raw,'/'));
        // rapikan double slash
        return (string) preg_replace('#/{2,}#', '/', $path);
    }

    /* ----------------------------- Scopes ----------------------------- */

    /** Ambil hanya slider yang aktif (mendukung 1/true/'active') */
    public function scopeActive($query)
    {
        return $query->where(function ($w) {
            $w->where('status', 1)
              ->orWhere('status', true)
              ->orWhere('status', 'active');
        });
    }

    /** Urutkan sesuai kolom 'order' kalau ada, fallback ID terbaru */
    public function scopeOrdered($query)
    {
        $table = $query->getModel()->getTable();
        if (Schema::hasColumn($table, 'order')) {
            return $query->orderBy('order')->orderByDesc('id');
        }
        return $query->orderByDesc('id');
    }

    /* --------------------------- Accessors ---------------------------- */

    /**
     * URL gambar yang aman:
     * - URL penuh (http/https) → langsung pakai
     * - storage/app/public/{path} → /storage/{path}
     * - public/{path} → /{path}
     * - fallback ke placeholder jika tidak ditemukan
     */
    public function getImageUrlAttribute(): string
    {
        $fallback = asset('images/placeholder.webp');
        $img = (string) ($this->image ?? '');

        if ($img === '') return $fallback;

        // Sudah URL penuh
        if (Str::startsWith($img, ['http://', 'https://', '//'])) {
            return $img;
        }

        $clean = $this->normalizePath($img);

        // Cek di storage (disk public) agar konsisten di semua environment
        if ($clean && Storage::disk('public')->exists($clean)) {
            return asset('storage/' . $clean);
        }

        // Cek file langsung di public/
        if ($clean && file_exists(public_path($clean))) {
            return asset($clean);
        }

        return $fallback;
    }

    /* --------------------------- Mutators ----------------------------- */

    /** Simpan path gambar sudah dinormalisasi (tanpa 'public/' atau 'storage/') */
    public function setImageAttribute($value): void
    {
        if (is_string($value)) {
            $value = $this->normalizePath($value);
        }
        $this->attributes['image'] = $value;
    }
}
