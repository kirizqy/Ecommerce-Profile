<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // GET /product/{id}
    public function detail($id)
    {
        // ambil produk + relasi kategori
        $product = Product::with('category')->findOrFail($id);

        // ambil daftar URL gambar dari accessor di model (images_urls)
        $images = collect($product->images_urls)->values()->all();

        // kirim ke view detail
        return view('frontend.product-detail', compact('product', 'images'));
    }
}
