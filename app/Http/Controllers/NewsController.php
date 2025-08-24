<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $placeholder = asset('images/placeholder.webp');
        $dateCol     = Schema::hasColumn('news', 'published_at') ? 'published_at' : 'created_at';

        // === Featured: 8 berita terbaru (cache 5 menit)
        $featuredNews = Cache::remember('news:featured:8', now()->addMinutes(5), function () use ($dateCol) {
            $q = News::query();

            // kalau model News punya scope published(), ini bisa diganti $q->published()
            if (Schema::hasColumn('news', 'status')) {
                $q->whereIn('status', [1, true, 'active', 'published']);
            }

            return $q->orderByDesc($dateCol)
                     ->take(8)
                     ->get();
        });

        // === Grid utama (paginate)
        $posts = News::published() // kamu sudah punya scope published()
            ->orderByDesc($dateCol)
            ->paginate(9);

        // AJAX append (infinite scroll)
        if ($request->ajax() && $request->get('append') === 'posts' && $request->has('page')) {
            return response()->view(
                'frontend.partials.news-cards',
                compact('posts', 'placeholder')
            );
        }

        return view('frontend.news', compact('posts', 'placeholder', 'featuredNews'));
    }

    public function show(Request $request, string $slug)
    {
        $item        = News::published()->where('slug', $slug)->firstOrFail();
        $placeholder = asset('images/placeholder.webp');

        // kalau request via AJAX → kirim PARTIAL detail
        if ($request->ajax()) {
            return response()->view(
                'frontend.partials.news-detail',
                compact('item', 'placeholder')
            );
        }

        // akses langsung (non-AJAX) → balik ke list
        return redirect()->route('news.index');
    }
}
