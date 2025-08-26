<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Slider;
use App\Models\News;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $placeholder = asset('images/placeholder.webp');

        /* === SLIDERS (3) === */
        $sliders = Cache::remember('home:sliders:3', now()->addMinutes(10), function () {
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

            return $q->orderByDesc('id')->take(3)->get();
        });

        if ($sliders->isEmpty()) {
            $sliders = collect(range(1,3))->map(fn($i) => (object)[
                'title'     => "Slide $i",
                'image_url' => $placeholder,
            ]);
        }

        /* === GALLERIES (6) === */
        $galleries = Cache::remember('home:galleries:random:6', now()->addMinutes(5), function () {
            return Gallery::query()->inRandomOrder()->limit(6)->get();
        });

        if ($galleries->isEmpty()) {
            $galleries = collect(range(1,6))->map(fn($i) => (object)[
                'title'    => "Gallery $i",
                'image'    => 'images/placeholder.webp', // biar cocok dengan Blade
                'is_video' => false,
            ]);
        }

        /* === PRODUCTS (6) === */
        $products = Cache::remember('home:products:random:6', now()->addMinutes(5), function () {
            return Product::query()->inRandomOrder()->limit(6)->get();
        });

        if ($products->isEmpty()) {
            $products = collect(range(1,6))->map(fn($i) => (object)[
                'id'        => $i,
                'name'      => "Product $i",
                'price'     => 100000 + ($i * 25000),
                'image_url' => $placeholder,
            ]);
        }

        /* === NEWS (3) — no cache === */
        $newsQ = News::query();
        if (Schema::hasColumn('news', 'status')) {
            $newsQ->where(function ($w) {
                $w->where('status', 1)
                  ->orWhere('status', true)
                  ->orWhere('status', 'published')
                  ->orWhere('status', 'publish');
            });
        }
        $news = $newsQ->latest('id')->limit(3)->get();

        if ($news->isEmpty()) {
            $news = collect([
                (object)[
                    'title'        => 'Launching New Collection',
                    'body'         => 'We just launched a fresh collection with modern motifs.',
                    'image_url'    => $placeholder,
                    'published_at' => Carbon::now(),
                    'created_at'   => Carbon::now(),
                ],
                (object)[
                    'title'        => 'Workshop with Community',
                    'body'         => 'We held an embroidery workshop with local crafters.',
                    'image_url'    => $placeholder,
                    'published_at' => Carbon::now()->subDays(2),
                    'created_at'   => Carbon::now()->subDays(2),
                ],
                (object)[
                    'title'        => 'New Partnership',
                    'body'         => 'We are partnering with local suppliers for faster delivery.',
                    'image_url'    => $placeholder,
                    'published_at' => Carbon::now()->subWeek(),
                    'created_at'   => Carbon::now()->subWeek(),
                ],
            ]);
        }

        return view('frontend.home', compact('sliders', 'galleries', 'products', 'news'));
    }
}
