<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\Gallery;

class GalleryController extends Controller
{
    /**
     * Tampilkan daftar gallery (gambar/video) dengan pagination + featured (8 terbaru).
     * - /gallery?sort=latest|random
     * - Support AJAX infinite scroll: jika request AJAX dengan ?page=2, kembalikan partial items saja
     */
    public function index(Request $request)
    {
        // ====== Featured: 8 item terbaru (cache 5 menit) ======
        // Cache key dipisahkan supaya tidak bentrok dengan list utama
        $featuredGalleries = Cache::remember('gallery:featured:8', now()->addMinutes(5), function () {
            $q = Gallery::query();

            // Jika ada kolom 'status' dan kamu memakainya, filter published/aktif
            if (Schema::hasColumn('galleries', 'status')) {
                $q->whereIn('status', [1, true, 'active', 'published']);
            }

            // Urutkan pakai created_at kalau ada; kalau tidak ada, pakai id
            $orderCol = Schema::hasColumn('galleries', 'created_at') ? 'created_at' : 'id';

            return $q->orderByDesc($orderCol)
                     ->take(8)
                     ->get();
        });

        // ====== Grid utama + pagination ======
        $sort = $request->query('sort', 'latest');
        if (!in_array($sort, ['latest', 'random'], true)) {
            $sort = 'latest';
        }

        $query = Gallery::query();

        if (Schema::hasColumn('galleries', 'status')) {
            $query->whereIn('status', [1, true, 'active', 'published']);
        }

        if ($sort === 'random') {
            $query->inRandomOrder();
        } else {
            $orderCol = Schema::hasColumn('galleries', 'created_at') ? 'created_at' : 'id';
            $query->orderByDesc($orderCol);
        }

        $galleries   = $query->paginate(12)->withQueryString();
        $placeholder = asset('images/placeholder.webp');

        // ====== AJAX (infinite scroll) → kirim partial items saja ======
        if ($request->ajax() && $request->has('page')) {
            return view('frontend.partials.gallery-items', [
                'galleries'   => $galleries,
                'placeholder' => $placeholder,
            ])->render();
        }

        // ====== Full page ======
        return view('frontend.gallery', [
            'galleries'         => $galleries,
            'featuredGalleries' => $featuredGalleries,
        ]);
    }
}
