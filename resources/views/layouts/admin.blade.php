<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Dashboard') | Bina Bordir Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- @vite(['resources/css/app.css','resources/js/admin.js']) --}}

  <!-- ASSET ADMIN VIA CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4/dist/css/adminlte.min.css">

  <style>
    :root{
      --bb:#FF8C00;           /* warna utama */
      --bbr:255,140,0;        /* rgb utk transparansi */
      --header-h:52px;
      --sidebar-w:240px;
    }

    body{ background:#f5f6fa; }
    .btn-primary{ background:var(--bb); border-color:var(--bb); }
    .btn-primary:hover{ background:#e57e00; border-color:#e57e00; }
    .card{ border-radius:.6rem; }
    .card-header{ background:#2f3542; color:#fff; padding:.55rem .75rem; }
    .card-body{ padding:.75rem; }
    .card-footer{ padding:.5rem .75rem; }

    /* ===== Header tipis & sticky ===== */
    .app-header.navbar{
      position:sticky; top:0; z-index:1030; height:var(--header-h);
      background:var(--bb)!important; padding-block:.35rem;
      box-shadow:0 2px 6px rgba(0,0,0,.06);
    }
    .app-header .navbar-brand,.app-header .nav-link{ color:#fff!important; }
    .app-header .nav-link:hover{ color:#000!important; }

    /* ===== Sidebar fixed ===== */
    .app-sidebar{
      position:fixed; top:var(--header-h); left:0;
      width:var(--sidebar-w); height:calc(100vh - var(--header-h));
      background:#1f1f1f; overflow-y:auto;
      transition:transform .25s ease;
    }
    .app-sidebar .sidebar-wrapper{ padding:.35rem .55rem .85rem; }
    .app-sidebar .nav.sidebar-menu .nav-item{ margin:3px 0; }
    .app-sidebar .nav-link{
      color:#adb5bd; padding:.45rem .6rem; border-radius:.55rem; font-size:.93rem; line-height:1.1;
    }
    .app-sidebar .nav-icon{ width:1.25rem; margin-right:.55rem; font-size:.95rem; color:var(--bb); }
    .app-sidebar .nav-link:hover{ background:rgba(255,255,255,.06); color:#fff!important; }
    .app-sidebar .nav-link.active{ background:rgba(var(--bbr),.18)!important; color:#fff!important; }

    .app-main{ margin-left:var(--sidebar-w); }
    .app-content-header{ padding:.5rem 0 .25rem; }
    .app-content-header h1{ font-size:1rem; margin:0; }
    .app-content{ padding:.75rem 0 1rem; }
    .app-footer{ margin-left:var(--sidebar-w); padding:.4rem .75rem; font-size:.85rem; }

    /* tombol burger disembunyikan default (muncul di HP via media query) */
    .btn-toggle-sidebar{ display:none!important; }

    /* ===== Brand/sidebar ===== */
    .sidebar-brand{ border-bottom:1px solid rgba(255,255,255,.06); }
    .brand-link{ color:#fff; filter:saturate(1.05); text-decoration:none; }
    .brand-link.disabled{ opacity:.6; cursor:not-allowed; pointer-events:none; }

    .brand-logo{
      width:38px; height:38px; border-radius:12px;
      background:linear-gradient(135deg, #FF8C00, #FFB24D);
      box-shadow:0 8px 24px rgba(255,140,0,.25), inset 0 1px 0 rgba(255,255,255,.08);
      font-weight:800; letter-spacing:.5px;
      text-transform:uppercase;
      display:inline-flex; align-items:center; justify-content:center;
    }
    .brand-logo span{ color:#111; font-size:.9rem; line-height:1; }

    .brand-title{ font-weight:700; font-size:1rem; letter-spacing:.2px; }
    .brand-sub{ color:#adb5bd; font-size:.72rem; text-transform:uppercase; letter-spacing:.1em; }
    .brand-link:hover .brand-title{ color:#FFB24D; }

    /* ===== Responsive: sidebar off-canvas di layar < 992px ===== */
    @media (max-width: 991.98px){
      .btn-toggle-sidebar{ display:inline-flex!important; align-items:center; }

      .app-sidebar{ transform: translateX(-100%); z-index:1040; }
      body.sidebar-open .app-sidebar{ transform: translateX(0); }

      .app-main,
      .app-footer{ margin-left:0; }

      .sidebar-backdrop{
        position: fixed; inset: 0; background: rgba(0,0,0,.35);
        z-index:1035; display:none;
      }
      body.sidebar-open .sidebar-backdrop{ display:block; }
    }
  </style>

  @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
@php
  use Illuminate\Support\Facades\Route;
  $hasDash        = Route::has('admin.dashboard');
  $hasProducts    = Route::has('admin.products.index');
  $hasCategories  = Route::has('admin.categories.index');
  $hasGalleries   = Route::has('admin.galleries.index');
  $hasContacts    = Route::has('admin.contacts.index');
  $hasSliders     = Route::has('admin.sliders.index');
  $hasNews        = Route::has('admin.news.index');
@endphp

<div class="app-wrapper">
  {{-- NAVBAR --}}
  <nav class="app-header navbar navbar-expand">
    <div class="container-fluid">
      <button class="btn btn-link d-lg-none btn-toggle-sidebar" type="button" aria-label="Menu">
        <i class="fa-solid fa-bars" style="color:#fff"></i>
      </button>

      <a href="{{ $hasDash ? route('admin.dashboard') : '#' }}"
         class="navbar-brand {{ $hasDash ? '' : 'disabled' }}"
         @if(!$hasDash) aria-disabled="true" @endif>
        <span class="brand-text fw-bold">Admin Panel</span>
      </a>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/') }}"><i class="fa-solid fa-house me-1"></i>Website</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" data-bs-toggle="dropdown" href="#"><i class="fa-regular fa-user"></i></a>
          <div class="dropdown-menu dropdown-menu-end">
            <span class="dropdown-item-text">Halo, {{ auth()->user()->name ?? 'Admin' }}</span>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('admin.logout') }}">
              @csrf
              <button class="dropdown-item text-danger">
                <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
              </button>
            </form>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  {{-- SIDEBAR --}}
  <aside class="app-sidebar shadow" data-bs-theme="dark">
    <div class="sidebar-brand px-3 py-3">
      <a href="{{ $hasDash ? route('admin.dashboard') : '#' }}"
         class="brand-link d-flex align-items-center gap-3 {{ $hasDash ? '' : 'disabled' }}"
         @if(!$hasDash) aria-disabled="true" @endif>
        <div class="brand-logo"><span>BB</span></div>
        <div class="lh-1">
          <div class="brand-title">Bina Bordir</div>
          <small class="brand-sub">Admin Panel</small>
        </div>
      </a>
    </div>

    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" role="menu">
          <li class="nav-item">
            <a href="{{ $hasDash ? route('admin.dashboard') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} {{ $hasDash ? '' : 'disabled' }}"
               @if(!$hasDash) aria-disabled="true" @endif>
              <i class="nav-icon fa-solid fa-gauge"></i><p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ $hasProducts ? route('admin.products.index') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }} {{ $hasProducts ? '' : 'disabled' }}"
               @if(!$hasProducts) aria-disabled="true" @endif>
              <i class="nav-icon fa-solid fa-box"></i><p>Produk</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ $hasCategories ? route('admin.categories.index') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} {{ $hasCategories ? '' : 'disabled' }}"
               @if(!$hasCategories) aria-disabled="true" @endif>
              <i class="nav-icon fa-solid fa-tags"></i><p>Kategori</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ $hasGalleries ? route('admin.galleries.index') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }} {{ $hasGalleries ? '' : 'disabled' }}"
               @if(!$hasGalleries) aria-disabled="true" @endif>
              <i class="nav-icon fa-regular fa-image"></i><p>Galeri</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ $hasContacts ? route('admin.contacts.index') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }} {{ $hasContacts ? '' : 'disabled' }}"
               @if(!$hasContacts) aria-disabled="true" @endif>
              <i class="nav-icon fa-regular fa-envelope"></i><p>Kontak</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ $hasSliders ? route('admin.sliders.index') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }} {{ $hasSliders ? '' : 'disabled' }}"
               @if(!$hasSliders) aria-disabled="true" @endif>
              <i class="nav-icon fa-solid fa-images"></i><p>Slider</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ $hasNews ? route('admin.news.index') : '#' }}"
               class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }} {{ $hasNews ? '' : 'disabled' }}"
               @if(!$hasNews) aria-disabled="true" @endif>
              <i class="nav-icon fa-regular fa-newspaper"></i><p>News</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  {{-- KONTEN --}}
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        @hasSection('header')
          @yield('header')
        @else
          <h1 class="h4 mb-0">@yield('title','Dashboard')</h1>
        @endif
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </main>

  <footer class="app-footer">
    <div class="float-end d-none d-sm-inline">v1.0</div>
    <strong>&copy; {{ date('Y') }} Bina Bordir.</strong> All rights reserved.
  </footer>
</div>

<!-- JS ADMIN -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4/dist/js/adminlte.min.js"></script>

{{-- Toggle sidebar di HP --}}
<script>
(function(){
  const btn = document.querySelector('.btn-toggle-sidebar');

  // Backdrop untuk menutup sidebar saat klik area gelap
  const backdrop = document.createElement('div');
  backdrop.className = 'sidebar-backdrop';
  document.body.appendChild(backdrop);

  function closeSidebar(){ document.body.classList.remove('sidebar-open'); }

  function initByWidth(){
    // Di HP sidebar tertutup default; di desktop juga tertutup (klik burger utk buka)
    closeSidebar();
  }

  if (btn) btn.addEventListener('click', function(){
    document.body.classList.toggle('sidebar-open');
  });

  backdrop.addEventListener('click', closeSidebar);

  initByWidth();
  window.addEventListener('resize', initByWidth);
})();
</script>

@stack('scripts')
</body>
</html>
