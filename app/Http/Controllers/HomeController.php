<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Slider;
use App\Models\News; // <-- tambahkan

class HomeController extends Controller
{
    public function index()
    {
        // Slider (cache 10 menit)
        $sliders = Cache::remember('home:sliders', now()->addMinutes(10), function () {
            $q = Slider::query();

            if (Schema::hasColumn('sliders', 'status')) {
                $q->where(function ($w) {
                    $w->where('status', 1)
                      ->orWhere('status', true)
                      ->orWhere('status', 'active');
                });
            }

            if (Schema::hasColumn('sliders', 'order')) {
                $q->orderBy('order');
            }

            return $q->orderByDesc('id')->take(10)->get();
        });

        // Gallery (cache 5 menit)
        $galleries = Cache::remember('home:galleries:random:6', now()->addMinutes(5), function () {
            return Gallery::query()->inRandomOrder()->limit(6)->get();
        });

        // Products (cache 5 menit)
        $products = Cache::remember('home:products:random:4', now()->addMinutes(5), function () {
            return Product::query()->inRandomOrder()->limit(4)->get();
        });

        // === NEWS (NO CACHE supaya tidak nyangkut) ===
        $newsQ = News::query();

        // kalau ada kolom status, ambil yang published/active
        if (Schema::hasColumn('news', 'status')) {
            $newsQ->where(function ($w) {
                $w->where('status', 1)
                  ->orWhere('status', true)
                  ->orWhere('status', 'published')
                  ->orWhere('status', 'publish');
            });
        }

        $news = $newsQ->latest('id')->limit(3)->get();

        return view('frontend.home', compact('sliders', 'galleries', 'products', 'news'));
    }
}
    