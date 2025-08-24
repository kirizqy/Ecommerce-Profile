<?php

use Illuminate\Support\Facades\Route;

// ==== Public Controllers ====
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;

// ==== Admin Controllers ====
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;

/*
|--------------------------------------------------------------------------
| Admin prefix dari config (pakai .env: ADMIN_PREFIX)
|--------------------------------------------------------------------------
*/
$adminPrefix = trim(config('app.admin_prefix', 'admin'), '/');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// ==== Public Routes ====
// ==== Public Routes ====

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/catalogue', [CatalogueController::class, 'index'])
    ->name('catalogue');

Route::get('/product/{id}', [ProductController::class, 'detail'])
    ->whereNumber('id') // hanya angka
    ->name('product.detail');

Route::get('/gallery', [GalleryController::class, 'index'])
    ->name('gallery.index');

Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');

Route::get('/news/{slug}', [NewsController::class, 'show'])
    ->name('news.show');

Route::view('/about', 'frontend.about')
    ->name('about');

Route::view('/contact', 'frontend.contact')
    ->name('contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| Admin Auth + Panel (prefix + name "admin.")
|--------------------------------------------------------------------------
*/
Route::prefix($adminPrefix)->name('admin.')->group(function () {
    // ==== GUEST ONLY ====
    Route::middleware('guest')->group(function () {
        Route::get ('/login', [AdminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminController::class, 'login'])
            ->middleware('throttle:10,1')
            ->name('login.attempt');
    });

    // ==== PROTECTED (auth + is_admin) ====
    Route::middleware(['auth','is_admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('root');

        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // shortcut
        Route::get('/catalogue', [AdminProductController::class, 'index'])->name('catalogue.index');

        // resources
        Route::resource('products',   AdminProductController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('galleries',  AdminGalleryController::class)->only(['index','create','store','destroy']);
        Route::resource('contacts',   AdminContactController::class)->only(['index','show','destroy']);
        Route::resource('sliders',    SliderController::class);
        Route::resource('news',       AdminNewsController::class);
    });
});
