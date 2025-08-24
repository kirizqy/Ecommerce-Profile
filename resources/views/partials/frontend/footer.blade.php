@php
  use Illuminate\Support\Facades\Route;

  $toHome      = Route::has('home')          ? route('home')          : url('/');
  $toAbout     = Route::has('about')         ? route('about')         : url('/about');
  $toCatalogue = Route::has('catalogue')     ? route('catalogue')     : url('/catalogue');
  $toGallery   = Route::has('gallery.index') ? route('gallery.index') : url('/gallery');
  $toContact   = Route::has('contact')       ? route('contact')       : url('/contact');

  // Amanin rute login admin (pakai named route kalau ada)
  $toAdminLogin = Route::has('admin.login') ? route('admin.login') : url('/admin/login');
@endphp

<footer style="background-color:#FF8C00;" class="text-white py-5">
  <div class="container">

    <div class="row">
      <!-- Brand & Tagline -->
      <div class="col-md-4 mb-4">
        <h5 class="mb-2">Bina Bordir</h5>
        <p class="mb-3">Luxurious Embroidery – Bordir eksklusif</p>
        <div>
          <a href="#" class="text-white me-3 fs-4 text-decoration-none"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-white me-3 fs-4 text-decoration-none"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-white me-3 fs-4 text-decoration-none"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-white me-3 fs-4 text-decoration-none"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      <!-- Menu -->
      <div class="col-md-4 mb-4">
        <h5 class="mb-3">Menu</h5>
        <div class="row">
          <div class="col-6">
            <p class="mb-1"><a href="{{ $toHome }}" class="text-white text-decoration-none">Home</a></p>
            <p class="mb-1"><a href="{{ $toAbout }}" class="text-white text-decoration-none">About Us</a></p>
            <p class="mb-1"><a href="{{ $toCatalogue }}" class="text-white text-decoration-none">Catalogue</a></p>
          </div>
          <div class="col-6">
            <p class="mb-1"><a href="{{ $toGallery }}" class="text-white text-decoration-none">Gallery</a></p>
            <p class="mb-1"><a href="{{ $toContact }}" class="text-white text-decoration-none">Contact</a></p>
          </div>
        </div>
      </div>

      <!-- Kontak -->
      <div class="col-md-4 mb-4">
        <h5 class="mb-3">Have a Questions?</h5>
        <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Sanggar Kencana XVI No. 5, Bandung, 40286</p>
        <p class="mb-1"><i class="fas fa-phone me-2"></i>6282117927000</p>
        <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@binabordir.com</p>
      </div>
    </div>

    <hr class="border-light">

    {{-- Copyright + link admin tersembunyi di teks "Bina Bordir" --}}
    <p class="text-center mb-0">
      &copy; {{ date('Y') }} 
      <span class="text-white">
        <a href="{{ $toAdminLogin }}"
           class="text-white text-decoration-none"
           style="font-weight:normal;">
          Bina Bordir
        </a>
      </span>. All rights reserved.
    </p>
  </div>
</footer>
