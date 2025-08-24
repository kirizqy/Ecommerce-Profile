<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'image', 'category_id',
        'shopee_link', 'tokopedia_link', 'whatsapp_link',
        'images',
    ];

    protected $casts = [
        'images'      => 'array',
        'price'       => 'decimal:2',
        'stock'       => 'integer',
        'category_id' => 'integer',
    ];

    // default agar images tidak null
    protected $attributes = [
        'images' => '[]',
    ];

    // ikut tampil pada toArray/toJson
    protected $appends = ['image_url', 'images_urls'];

    /* ================== Hooks ================== */
    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            // NOTE: jika pakai SoftDeletes, pindahkan logika ini ke forceDeleted()
            $product->deleteFileIfExists($product->image);

            $arr = is_array($product->images) ? $product->images : [];
            foreach ($arr as $raw) {
                $product->deleteFileIfExists($raw);
            }
        });
    }

    /* ================== Relations ================== */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /* ================== Accessors ================== */

    /** URL gambar utama (pakai image; jika kosong pakai images[0]; jika tidak ada → placeholder) */
    public function getImageUrlAttribute(): string
    {
        // 1) cover langsung
        $raw = trim((string) ($this->image ?? ''));
        if ($raw !== '') {
            return $this->pathToUrl($raw);
        }

        // 2) fallback ke gambar pertama dari array images
        $arr = is_array($this->images) ? $this->images : [];
        if (count($arr) > 0 && $arr[0]) {
            return $this->pathToUrl((string) $arr[0]);
        }

        // 3) placeholder
        return $this->fallbackImage();
    }

    /** URL list semua gambar (unique + urut: cover dulu, lalu images) */
    public function getImagesUrlsAttribute(): array
    {
        $urls = [];

        // a) cover bila ada
        $rawCover = trim((string) ($this->image ?? ''));
        if ($rawCover !== '') {
            $urls[] = $this->pathToUrl($rawCover);
        }

        // b) dari JSON images
        $arr = is_array($this->images) ? $this->images : [];
        foreach ($arr as $raw) {
            if (!$raw) continue;
            $urls[] = $this->pathToUrl((string) $raw);
        }

        // c) kalau kosong, kasih 1 placeholder
        if (empty($urls)) $urls[] = $this->fallbackImage();

        // unique & reindex
        return array_values(array_unique($urls));
    }

    /* ================== Mutators ================== */

    /** Rapikan & batasi images maksimal 8 item */
    public function setImagesAttribute($value): void
    {
        $arr = is_array($value) ? $value : (array) $value;
        $clean = [];

        foreach ($arr as $raw) {
            if (!$raw) continue;
            $raw = (string) $raw;

            if (Str::startsWith($raw, ['http://','https://'])) {
                $clean[] = trim($raw);
                continue;
            }

            $clean[] = $this->normalizePath($raw);
        }

        // unique + limit 8
        $clean = array_values(array_unique(array_filter($clean)));
        $clean = array_slice($clean, 0, 8);

        // simpan sebagai JSON string (biar konsisten dengan cast)
        $this->attributes['images'] = json_encode($clean);
    }

    /* ================== Helpers ================== */

    private function deleteFileIfExists(?string $raw): void
    {
        if (!$raw) return;

        // lewati URL eksternal
        if (Str::startsWith($raw, ['http://', 'https://'])) return;

        $path = $this->normalizePath($raw);
        if ($path === '') return;

        // prioritas di disk public (storage/app/public)
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        // fallback: jika (entah kenapa) file ada langsung di /public
        $publicPath = public_path($path);
        if (is_file($publicPath)) @unlink($publicPath);
    }

    /** Ubah path/file relatif menjadi URL yang bisa dipakai <img src> */
    private function pathToUrl(string $raw): string
    {
        // URL absolut langsung balikin
        if (Str::startsWith($raw, ['http://','https://'])) return trim($raw);

        $path = $this->normalizePath($raw);

        // Cek di disk public (storage/app/public)
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        // Cek fallback: file ada di /public
        if ($path && is_file(public_path($path))) {
            return asset($path);
        }

        return $this->fallbackImage();
    }

    /** Bersihkan prefix umum & normalisasi slash */
    private function normalizePath(string $raw): string
    {
        $raw = str_replace('\\', '/', $raw);
        $raw = trim($raw);
        $raw = ltrim($raw, '/');

        // buang prefix umum: public/, storage/, public/storage/
        // hasil akhirnya diharapkan relatif terhadap disk 'public' (tanpa 'storage/')
        $raw = preg_replace('#^(public/)?(storage/)?#', '', $raw) ?? $raw;

        // rapikan double slash
        $raw = preg_replace('#/{2,}#', '/', $raw) ?? $raw;

        return (string) $raw;
    }

    private function fallbackImage(): string
    {
        $local = public_path('images/placeholder.webp');
        return is_file($local)
            ? asset('images/placeholder.webp')
            : 'https://placehold.co/800x600?text=No+Image';
    }

    
}
