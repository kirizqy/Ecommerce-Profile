<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        // ambil ringkasan & 5 produk terbaru (dengan kategori)
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $latestProducts  = Product::with('category')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'latestProducts'
        ));
    }
}
