<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use App\Models\Category;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        // ------------ Input ------------
        $categoryId = (int) $request->query('category', 0);
        if ($categoryId <= 0) { $categoryId = null; }

        $q    = trim((string) $request->query('q', ''));
        $sort = (string) $request->query('sort', 'most');
        $sort = in_array($sort, ['most','random'], true) ? $sort : 'most';

        // ------------ Sidebar kategori ------------
        $categories = Category::query()
            ->select(['id','name'])
            ->orderBy('name')
            ->get();

        // Jika user pasang category yang tidak ada, netralkan agar tidak meledak
        if ($categoryId && !$categories->firstWhere('id', $categoryId)) {
            $categoryId = null;
        }

        // ------------ Query produk ------------
        $productsQuery = Product::query()
            ->with('category')
            ->when($categoryId, fn($qr) => $qr->where('category_id', $categoryId))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            });

        // Urutan
        if ($sort === 'random') {
            $productsQuery->inRandomOrder();
        } else {
            // aman jika kolom "views" tidak ada
            if (Schema::hasColumn('products', 'views')) {
                $productsQuery->orderByDesc('views')->orderByDesc('id');
            } else {
                $productsQuery->orderByDesc('id');
            }
        }

        // ------------ Pagination ------------
        $products = $productsQuery->paginate(12)->withQueryString();

        // ------------ AJAX (partial) ------------
        if ($request->ajax()) {
            // kalau partial belum ada, fallback ke list minimal supaya tidak error
            if (View::exists('frontend.partials.catalogue-items')) {
                return response()->view('frontend.partials.catalogue-items', [
                    'products'    => $products,
                    'placeholder' => asset('images/placeholder.webp'),
                ]);
            }

            // fallback aman
            return response()->view('frontend.catalogue', [
                'products'   => $products,
                'categories' => $categories,
                'categoryId' => $categoryId,
                'q'          => $q,
                'sort'       => $sort,
            ]);
        }

        // ------------ Full page ------------
        return view('frontend.catalogue', [
            'products'   => $products,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'q'          => $q,
            'sort'       => $sort,
        ]);
    }
}
