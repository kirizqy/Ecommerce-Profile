<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Category;

class PageController extends Controller
{
    public function home()
    {
        $products = Product::latest()->take(6)->get();
        $galleries = Gallery::latest()->take(6)->get();
        $sliders = Slider::latest()->take(3)->get();

        return view('frontend.home', compact('products', 'galleries', 'sliders'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function catalogue(Request $request)
    {
        $categoryId = $request->get('category');
        $categories = Category::all();

        $products = Product::when($categoryId, function ($query) use ($categoryId) {
            return $query->where('category_id', $categoryId);
        })->latest()->get();

        return view('frontend.catalogue', compact('products', 'categories', 'categoryId'));
    }

    public function gallery()
    {
        // Simulasi array foto, nanti bisa dari database
        $gallery = Gallery::latest()->get();
        return view('frontend.gallery', compact('gallery'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function productDetail($id)
    {
        $product = Product::findOrFail($id);

        $adminPhoneNumber = '6282117927000';

        return view('frontend.product-detail', compact('product'));
    }
}
