<!-- resources/views/partials/navbar.blade.php -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top shadow-sm py-2">
  <div class="container d-flex justify-content-between align-items-center">
    
    <!-- Brand Placeholder (tanpa logo gambar) -->
    <div class="d-flex align-items-center">
      <div class="d-flex flex-column lh-sm">
        <span class="fw-bold fs-4 text-white">BINA BORDIR</span>
        <small class="text-white">Luxurious Embroidery - Bordir eksklusif</small>
      </div>
    </div>

    <!-- Toggle untuk mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link text-white" href="{{ url('/') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ url('/about') }}">About Us</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ url('/catalogue') }}">Catalogue</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ url('/gallery') }}">Gallery</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ url('/contact') }}">Contact</a></li>
      </ul>
    </div>

  </div>
</nav>