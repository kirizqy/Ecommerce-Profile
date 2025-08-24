  <!doctype html>
  <html lang="id">
  <head>
    <meta charset="utf-8">
    <title>@yield('title','Bina Bordir')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">

    <style>
      :root{ --bb-primary:#ff7b00; --bb-primary-600:#e46e00; }
      body{ background:#f7f8fb; }

      /* ===== NAVBAR (glass + underline animasi) ===== */
      .bb-navbar{
        background: linear-gradient(180deg, rgba(255,255,255,.9), rgba(255,255,255,.65));
        backdrop-filter: blur(8px);
        border-bottom: 2px solid rgba(255,123,0,.12);
        box-shadow: 0 8px 24px rgba(0,0,0,.06);
        transition: padding .18s ease;
        padding:.45rem 0;
      }
      .bb-navbar.shrink{ padding:.15rem 0; }

      .bb-navbar .navbar-brand{
        color:#fff; background:var(--bb-primary);
        padding:.35rem .7rem; border-radius:.7rem; font-weight:800; letter-spacing:.3px;
      }
      .bb-navbar .nav-link{
        position:relative; font-weight:600; color:#222; padding:.6rem .9rem;
        border-radius:.6rem; transition:color .18s ease, background .18s ease;
      }
      .bb-navbar .nav-link:hover{ color:var(--bb-primary); background:rgba(255,123,0,.08); }
      .bb-navbar .nav-link::after{
        content:""; position:absolute; left:12px; right:12px; bottom:.3rem; height:2px;
        background:var(--bb-primary); transform:scaleX(0); transform-origin:left; transition:transform .18s ease;
      }
      .bb-navbar .nav-link:hover::after{ transform:scaleX(1); }
      .bb-navbar .nav-link.is-active{ color:#fff; background:var(--bb-primary); }
      .bb-navbar .nav-link.is-active::after{ display:none; }

      /* ===== SLIDER base (dipakai di home) ===== */
      #hero-carousel .carousel-item img{
        height:520px; object-fit:cover; width:100%;
        cursor:pointer; transition:transform .18s ease, box-shadow .18s ease; will-change: transform;
        border-radius:.8rem;
      }
      #hero-carousel .carousel-item img.pop{ transform: scale(1.035); box-shadow: 0 18px 44px rgba(0,0,0,.22); }
      .slide-caption{
        position:absolute; inset:auto 0 14%; text-align:center; color:#fff;
        text-shadow:0 3px 16px rgba(0,0,0,.55); font-weight:700;
      }

      .section-title{ color:var(--bb-primary); font-weight:800; }

      /* ===== FOOTER ===== */
      .site-footer{ background:#fff; }
      .site-footer .footer-grad{
        height:10px;
        background:linear-gradient(90deg,#de4312 0%, #fec163 100%);
      }
      .site-footer .footer-inner{
        max-width:1100px; margin:0 auto; padding:14px 16px;
        display:flex; justify-content:center; align-items:center;
        color:#6b7280; font-size:.95rem;
      }
      .site-footer .copyright a{
        color:inherit; text-decoration:none; font-weight:normal;
      }
      .site-footer .copyright a:hover{ text-decoration:underline; }

      /* ===== NORMALISASI GAMBAR DI KARTU ===== */
      .card-img-top,
      .product-thumb,
      .gallery-thumb,
      .news-thumb{
        width:100%;
        aspect-ratio:1/1;
        height:auto;
        object-fit:cover;
        display:block;
        border-radius:12px;
      }
      .slider-thumb{ aspect-ratio:16/9; object-fit:cover; }

/* ===== NEWS CARD FIX HEIGHT ===== */
.news-card img{
  width:100%;
  height:260px;             /* atur tinggi tetap */
  aspect-ratio:auto !important; /* override aturan global yang 1:1 */
  object-fit:cover;         /* crop rapi */
  border-radius:.5rem;
}

@media (min-width: 992px){
  .news-card img{ height:320px; } /* lebih tinggi di layar besar (opsional) */
}

.bb-navbar .navbar-brand .brand-logo {
  height: 50px;       /* kecilin tinggi logo */
  width: auto;        /* biar proporsional */
  border-radius: 0;   /* hapus sudut rounded kalau ada */
  object-fit: contain;
  background: none;   /* pastikan nggak ada background */
}

/* LOGO BRAND NAVBAR */
.bb-navbar .navbar-brand .brand-logo {
  height: 24px;          /* lebih kecil biar seimbang */
  width: 24px;           /* biar bulat konsisten */
  object-fit: cover;     /* logo dipotong pas lingkaran */
  border: 2px solid #fff; /* bingkai putih biar elegan */
  border-radius: 50%;    /* bulat sempurna */
  padding: 2px;          /* jarak logo ke bingkai */
  background: #ff7b00;   /* latar oranye supaya nyatu */
  box-shadow: 0 0 4px rgba(0,0,0,.15); /* efek halus */
}

/* ====== HORIZONTAL SCROLLER (Products) ====== */
.bb-scroller-wrap { position: relative; }
.bb-scroller {
  display: grid;
  grid-auto-flow: column;
  grid-auto-columns: 160px;   /* ukuran kartu default */
  gap: 14px;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  padding-bottom: 6px;
}
@media (min-width: 576px){ .bb-scroller{ grid-auto-columns: 180px; } }
@media (min-width: 768px){ .bb-scroller{ grid-auto-columns: 200px; } }
@media (min-width: 992px){ .bb-scroller{ grid-auto-columns: 220px; } }

.hide-scrollbar { scrollbar-width: none; }
.hide-scrollbar::-webkit-scrollbar { display: none; }

.bb-prod-card{
  scroll-snap-align: start;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 10px 24px rgba(0,0,0,.08);
  transition: transform .18s ease, box-shadow .18s ease;
  background: #fff;
}
.bb-prod-card:hover{
  transform: translateY(-2px);
  box-shadow: 0 16px 36px rgba(0,0,0,.12);
}
.bb-prod-card img{
  width: 100%; height: 180px; object-fit: cover; display: block;
}
@media (min-width: 768px){ .bb-prod-card img{ height: 200px; } }

.bb-scroll-btn{
  border: none;
  background: rgba(0,0,0,.06);
  width: 36px; height: 36px;
  border-radius: 999px;
  display: inline-flex; align-items: center; justify-content: center;
}
.bb-scroll-btn:hover{ background: rgba(0,0,0,.12); }

/* Biar judul Our Products putih */
#home-products .section-title {
  color: #fff !important;
  font-weight: 800;
}
#home-products .text-muted {
  color: rgba(255,255,255,.85) !important;
}

/* biar bisa geser pakai jari di mobile */
.bb-scroller{
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scroll-snap-type: x mandatory;
  touch-action: pan-x pinch-zoom; /* izinkan swipe horizontal */
}
.bb-scroller.is-dragging{
  cursor: grabbing;
  user-select: none;
}


    </style>

    @stack('head')
  </head>
  <body>

  <nav class="bb-navbar navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
      <img src="{{ asset('images/logo-binabordir.webp') }}" alt="Logo Bina Bordir" class="brand-logo me-2">
      <span>BINA BORDIR</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}"
              href="{{ route('home') }}">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}"
              href="{{ route('about') }}">About Us</a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('catalogue') ? 'is-active' : '' }}"
              href="{{ route('catalogue') }}">Catalogue</a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('gallery.index') ? 'is-active' : '' }}"
              href="{{ route('gallery.index') }}">Gallery</a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('news.index') ? 'is-active' : '' }}"
              href="{{ route('news.index') }}">News</a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}"
              href="{{ route('contact') }}">Contact</a>
          </li>
          {{-- tidak menaruh login admin di navbar --}}
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    @yield('content')
  </main>

  <footer class="site-footer">
    <div class="footer-grad"></div>
    <div class="container footer-inner">
      <div class="copyright text-center w-100">
        © {{ date('Y') }}
        <a href="{{ \Illuminate\Support\Facades\Route::has('admin.login')
                    ? route('admin.login')
                    : url('/'.config('app.admin_prefix','admin').'/login') }}"
          title="Admin Login">Bina Bordir</a>.
        All rights reserved.
      </div>
    </div>
  </footer>

  <!-- Lightbox Modal (dipakai oleh home) -->
  <div class="modal fade" id="lightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content bg-transparent border-0">
        <button type="button" class="btn btn-light ms-auto me-2 mt-2"
                data-bs-dismiss="modal" aria-label="Close"
                style="border-radius:999px;width:40px;height:40px;display:flex;align-items:center;justify-content:center">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <img id="lightboxImg" src="" class="img-fluid rounded shadow-lg" alt="preview">
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // navbar shrink on scroll
    const navbar = document.querySelector('.bb-navbar');
    addEventListener('scroll', () => {
      if (scrollY > 10) navbar.classList.add('shrink'); else navbar.classList.remove('shrink');
    });

    // global lightbox handler
    window.openLightbox = function(src){
      const modalImg = document.getElementById('lightboxImg');
      modalImg.src = src;
      const modal = new bootstrap.Modal('#lightbox');
      modal.show();
    };
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // ... script shrink navbar & lightbox ...
</script>

<script>
  // tombol panah (tetap)
  document.querySelectorAll('.bb-scroll-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id  = btn.getAttribute('data-scroll');
      const dir = parseInt(btn.getAttribute('data-dir'), 10) || 1;
      const el  = document.getElementById(id);
      if (!el) return;
      el.scrollBy({ left: dir * Math.max(240, el.clientWidth * 0.9), behavior: 'smooth' });
    });
  });

  // swipe/drag pakai Pointer Events (works: touch + mouse)
  document.querySelectorAll('.bb-scroller').forEach(el => {
    let isDown = false, startX = 0, startLeft = 0;

    el.addEventListener('pointerdown', (e) => {
      isDown = true;
      startX = e.clientX;
      startLeft = el.scrollLeft;
      el.setPointerCapture(e.pointerId);
      el.classList.add('is-dragging');
    });

    el.addEventListener('pointermove', (e) => {
      if (!isDown) return;
      const dx = e.clientX - startX;
      el.scrollLeft = startLeft - dx; // geser mengikuti jari
    });

    const end = () => { isDown = false; el.classList.remove('is-dragging'); };
    el.addEventListener('pointerup', end);
    el.addEventListener('pointercancel', end);

    // scroll wheel → horizontal (desktop), tidak mengganggu mobile
    el.addEventListener('wheel', (e) => {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        e.preventDefault();
        el.scrollBy({ left: e.deltaY, behavior: 'smooth' });
      }
    }, { passive: false });
  });
</script>

  @stack('body')
  </body>
  </html>
