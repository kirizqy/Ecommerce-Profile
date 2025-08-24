<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\Paginator;            // <- tambahkan ini
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Slider;
use App\Models\News;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // === Aktifkan style pagination Bootstrap 5 untuk {{ $paginator->links() }} ===
        Paginator::useBootstrapFive();

        // Bersihkan semua cache yang dipakai di homepage & featured list
        $flushHomeCache = function (): void {
            // Home caches
            Cache::forget('home:sliders');
            Cache::forget('home:galleries:random:6');
            Cache::forget('home:products:random:6');
            Cache::forget('home:v1'); // kalau nanti kamu pakai cache gabungan

            // Featured caches (halaman Gallery & News)
            Cache::forget('gallery:featured:8');
            Cache::forget('news:featured:8');

            // Opsional: kalau Spatie ResponseCache terpasang, bersihkan juga
            $respCacheClass = 'Spatie\\ResponseCache\\ResponseCache';
            if (class_exists($respCacheClass)) {
                try {
                    app()->make($respCacheClass)->clear();
                } catch (\Throwable $e) {
                    // diamkan saja; tidak kritis
                }
            }
        };

        // Pasang listener pada model-model yang dipakai
        foreach ([Product::class, Gallery::class, Slider::class, News::class] as $model) {
            if (!class_exists($model)) {
                continue;
            }

            // on create/update
            $model::saved($flushHomeCache);

            // on delete
            $model::deleted($flushHomeCache);

            // on restore (hanya jika model pakai SoftDeletes)
            if (in_array(SoftDeletes::class, class_uses_recursive($model), true)) {
                $model::restored($flushHomeCache);
            }
        }
    }
}
