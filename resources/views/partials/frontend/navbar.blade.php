
<nav class="navbar navbar-expand-lg navbar-dark site-nav sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold"
       href="{{ Route::has('home') ? route('home') : url('/') }}">
      BINA BORDIR
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto gap-lg-2">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
             href="{{ Route::has('home') ? route('home') : url('/') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
             href="{{ Route::has('about') ? route('about') : url('/about') }}">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('catalogue.*') ? 'active' : '' }}"
             href="{{ Route::has('catalogue.index') ? route('catalogue.index') : url('/catalogue') }}">Catalogue</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}"
             href="{{ Route::has('gallery') ? route('gallery') : url('/gallery') }}">Gallery</a>
        </li>
                  <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}"
              href="{{ Route::has('news') ? route('news') : url('/news') }}">News</a>
          </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
             href="{{ Route::has('contact') ? route('contact') : url('/contact') }}">Contact</a>
        </li>
        {{-- Tidak ada link Admin --}}
      </ul>
    </div>
  </div>
</nav>
