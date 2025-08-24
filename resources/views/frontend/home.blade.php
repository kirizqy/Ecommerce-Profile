@extends('layouts.frontend')
@section('title','Home')

@push('head')
  {{-- Google Fonts: Sora (judul) + Inter (body/deskripsi) --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
@php
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Str;
  

  $slides      = ($sliders ?? collect());
  $galleries   = ($galleries ?? collect());
  $products    = ($products ?? collect());
  $newsItems   = ($news ?? collect());

  $toGallery   = Route::has('gallery.index') ? route('gallery.index') : url('/gallery');
  $toCatalog   = Route::has('catalogue')     ? route('catalogue')     : url('/catalogue');
  $toAbout     = Route::has('about')         ? route('about')         : url('/about');
  $toNews      = Route::has('news.index')    ? route('news.index')    : url('/news');

  $placeholder = asset('images/placeholder.webp');
@endphp

<style>
  :root{
    --grad-start:#de4312;
    --grad-end:#fec163;
    --brand:#ff7b00;
    --brand-rgb:255,123,0;
    --brand-dark:#b55600;
    --text:#1b1b1b;
    --muted:#6b7280;
    --radius:16px;

    /* === font + warna teks utama === */
    --font-head:'Sora', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    --font-body:'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;

    /* >>> UBAH WARNA DI SINI KALAU MAU <<< */
    --head-color:#0f172a;   /* warna judul item (produk/news/about) | bisa diganti var(--brand) */
    --desc-color:#334155;   /* warna deskripsi/meta (slate-700) */
  }

  html, body{ height:100%; }
  body{
    background: linear-gradient(90deg, var(--grad-start) 0%, var(--grad-end) 100%);
    background-attachment: fixed;
    color:#fff;
    font-family: var(--font-body);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  .page{ color:#fff; }
  .container-narrow{ 
  max-width:1100px; 
  margin-inline:auto; 
  padding:0 1rem; 
}
[id^="section-"]{ scroll-margin-top:72px; }

/* ==== khusus News biar lebih lebar ==== */
.container-news{
  max-width:1100px;   /* atau 1200/1400 px sesuai selera */
  margin-inline:auto;
  padding:0 1rem;
}

  /* ===== HERO ===== */
  .hero{ position:relative; color:#fff; margin-top:-2px; }
  .hero .slick{ position:relative; height: clamp(520px, 70vh, 760px); overflow:hidden; }
  @media (max-width: 768px){ .hero .slick{ height: clamp(420px, 60vh, 560px); } }
  @media (max-width: 480px){ .hero .slick{ height: 380px; } }
  .hero .slick .item{ position:absolute; inset:0; opacity:0; transform:scale(1.02); transition:.6s; }
  .hero .slick .item.is-active{ opacity:1; transform:scale(1); }
  .hero .slick .item img{ width:100%; height:100%; object-fit:cover; filter:contrast(1.03); }
  .hero .overlay{ position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.55), rgba(0,0,0,.25) 48%, rgba(0,0,0,0)); }
  .hero .caption{ position:absolute; inset-inline:0; bottom:54px; display:flex; justify-content:center; z-index:3; }
  .hero .pill{ background:rgba(255,255,255,.95); color:#111; padding:.45rem .85rem; border-radius:999px; display:inline-flex; gap:.5rem; align-items:center; font-weight:800; box-shadow:0 12px 24px rgba(0,0,0,.14); font-family:var(--font-head); }
  .hero .dot-pill{ width:8px; height:8px; border-radius:99px; background:var(--brand); }
  .hero .nav-btn{ position:absolute; top:50%; transform:translateY(-50%); width:42px; height:42px; border-radius:999px; border:none; background:rgba(255,255,255,.9); color:#111; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(0,0,0,.25); cursor:pointer; z-index:3; }
  .hero .nav-prev{ left:12px; } .hero .nav-next{ right:12px; }
  .hero .dots{ position:absolute; left:50%; bottom:16px; transform:translateX(-50%); display:flex; gap:8px; z-index:3; }
  .hero .dots .dot{ width:10px; height:10px; border-radius:999px; background:rgba(255,255,255,.55); border:2px solid rgba(0,0,0,.12); cursor:pointer; }
  .hero .dots .dot.is-active{ background:#ff7b00; }

  /* ===== TITLES (section) ===== */
  .section{ padding:56px 0; }
  .title{ display:flex; align-items:center; gap:.6rem; margin-bottom:14px; }
  .title h2{
    margin:0; color:#fff; text-shadow:0 1px 0 rgba(0,0,0,.2);
    font-family: var(--font-head); font-weight: 800; letter-spacing:.2px;
    font-size: clamp(1.35rem, 1.1rem + 1.4vw, 2.25rem);
    line-height: 1.1;
  }
  .subtitle{
    color:rgba(255,255,255,.92);
    margin:.25rem 0 22px;
    font-family: var(--font-body);
    font-weight: 500;
    letter-spacing:.2px;
    font-size: clamp(.98rem, .92rem + .25vw, 1.1rem);
  }

  /* ===== CTA ===== */
  .btn, .btn:visited{ text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; }
  .btn-cta{
    background:linear-gradient(90deg,#ff7b00,#ff9a3d);
    color:#fff !important; font-weight:900; padding:.7rem 1.25rem;
    border-radius:999px; border:none; box-shadow:0 14px 30px rgba(0,0,0,.25);
    transition:transform .2s ease, box-shadow .2s ease, opacity .2s ease;
    font-family: var(--font-head); letter-spacing:.2px;
  }
  .btn-cta:hover{ transform:translateY(-2px); box-shadow:0 20px 44px rgba(0,0,0,.32); opacity:.96; }

  /* ===== GALLERY STRIP ===== */
  .strip{ display:grid; grid-auto-flow:column; grid-auto-columns:280px; gap:1.2rem; overflow-x:auto; padding-bottom:.5rem; scroll-snap-type:x mandatory; }
  .strip::-webkit-scrollbar{ height:8px } .strip::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.35); border-radius:99px }
  .g-thumb{ position:relative; scroll-snap-align:start; border-radius:20px; background:rgba(255,255,255,.96); padding:14px; box-shadow:0 18px 38px rgba(0,0,0,.25); border:1px solid rgba(0,0,0,.06); }
  .g-thumb .frame{ position:relative; overflow:hidden; border-radius:14px; outline:2px solid rgba(0,0,0,.06); }
  .g-media{ width:100%; height:260px; object-fit:cover; display:block; }
  @media (max-width: 768px){ .strip{ grid-auto-columns:200px; } .g-media{ height:200px; } }
  @media (max-width: 480px){ .strip{ grid-auto-columns:170px; } .g-media{ height:170px; } }
  @media (min-width: 1200px){ .strip{ grid-auto-columns:300px; } .g-media{ height:280px; } }

  /* ===== PRODUCTS ===== */
  .grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1.1rem; }
  .card{ border:1px solid rgba(0,0,0,.06); border-radius:var(--radius); background:#fff; color:var(--text); box-shadow:0 18px 36px rgba(0,0,0,.22); overflow:hidden; transition:transform .15s ease, box-shadow .15s ease; }
  .card:hover{ transform:translateY(-4px); box-shadow:0 24px 46px rgba(0,0,0,.28); }
  .media{ position:relative; aspect-ratio:1/1; overflow:hidden; }
  .media img{ width:100%; height:100%; object-fit:cover; display:block; }

  /* default badge kecil (kalau masih dipakai) */
  .badge{ position:absolute; left:10px; top:10px; background:rgba(0,0,0,.78); color:#fff; padding:.28rem .55rem; border-radius:8px; font-size:.8rem; }

  /* Harga */
  .price{ position:absolute; right:10px; bottom:10px; background:rgba(var(--brand-rgb),.98); color:#fff; padding:.42rem .68rem; border-radius:10px; font-weight:800; box-shadow:0 6px 12px rgba(var(--brand-rgb),.35); font-family:var(--font-head); }

  /* Badge SOLD OUT besar di tengah */
  .soldout-badge{
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    background:rgba(0,0,0,.78); color:#fff; border-radius:12px;
    padding:.6rem 1.1rem; font-weight:900; letter-spacing:.08em;
    font-size: clamp(1.05rem, .9rem + .9vw, 1.6rem);
    box-shadow:0 10px 26px rgba(0,0,0,.28);
    text-transform:uppercase;
    pointer-events:none;
  }

  .body{ padding:1rem 1rem 1.1rem; }

  /* judul item (produk) */
  .name{
    margin:0 0 .35rem;
    color:var(--head-color);
    font-family: var(--font-head);
    font-weight: 800;
    letter-spacing:.25px;
    line-height:1.22;
    font-size: clamp(1.15rem, 1.02rem + .7vw, 1.55rem);
  }
  /* deskripsi kecil/kategori */
  .meta{
    font-size: clamp(.95rem, .92rem + .2vw, 1.05rem);
    color:var(--desc-color);
    font-family: var(--font-body);
    letter-spacing:.18px;
  }

  /* ===== ABOUT ===== */
  .about-card{ position:relative; border-radius:22px; overflow:hidden; background:rgba(255,255,255,.96); color:#111; backdrop-filter: blur(6px); box-shadow:0 22px 48px rgba(0,0,0,.28); border:1px solid rgba(255,255,255,.7); }
  .about-wrap{ display:grid; grid-template-columns: 1.1fr .9fr; gap:1rem; padding:1.25rem; }
  @media (max-width: 768px){ .about-wrap{ grid-template-columns:1fr; } }
  .about-title h3{
    margin:0; color:var(--head-color);
    font-family: var(--font-head);
    font-weight: 800;
    letter-spacing:.2px;
    font-size: clamp(1.25rem, 1.1rem + .8vw, 1.8rem);
    line-height:1.15;
  }
  .about-pill{ display:inline-flex; align-items:center; gap:.4rem; font-weight:700; color:#fff; background:linear-gradient(90deg,#ff7b00,#ff9a3d); padding:.38rem .75rem; border-radius:999px; font-family:var(--font-head); }
  .about-p{
    margin:0; color:var(--desc-color);
    font-family: var(--font-body);
    font-weight: 500;
    letter-spacing:.12px;
    font-size: clamp(1rem, .96rem + .25vw, 1.08rem);
    line-height: 1.78;
  }
  .badge-chip{ background:rgba(0,0,0,.06); border:1px solid rgba(0,0,0,.08); color:#222; padding:.38rem .62rem; border-radius:999px; font-weight:700; font-size:.92rem; font-family:var(--font-head); }

  /* ===== NEWS ===== */
  .news-list{ display:grid; gap:1rem; }
  .news-card{ display:flex; gap:1rem; align-items:stretch; border:1px solid rgba(0,0,0,.06); border-radius:16px; overflow:hidden; background:#fff; color:#111; box-shadow:0 12px 28px rgba(0,0,0,.18); transition:transform .15s ease, box-shadow .15s ease; }
  .news-card:hover{ transform:translateY(-3px); box-shadow:0 20px 40px rgba(0,0,0,.23); }
  .news-thumb{ flex:0 0 200px; height:200px; overflow:hidden; background:#f6f6f6; display:block; }
  .news-thumb img{ width:100%; height:100%; object-fit:cover; display:block; }

  .news-body{ padding:1rem; flex:1 1 auto; display:flex; flex-direction:column; }
  .news-title{
    margin:0; color:var(--head-color);
    font-family: var(--font-head);
    font-weight: 800;
    letter-spacing:.25px;
    font-size: clamp(1.15rem, 1.05rem + .6vw, 1.6rem);
    line-height: 1.2;
  }
  .news-meta{ font-size:.9rem; color:#64748b; margin:.25rem 0 .5rem; font-family:var(--font-body); }
  .news-excerpt{
    font-size: clamp(1rem, .98rem + .2vw, 1.06rem);
    color:var(--desc-color);
    line-height:1.75;
    display:-webkit-box; -webkit-line-clamp:3; line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;
    font-family: var(--font-body);
    letter-spacing:.12px;
  }
  @media(max-width:576px){ .news-thumb{ flex-basis:110px; height:110px; } }
</style>

<div class="page">

  {{-- ===== HERO (SLIDER) ===== --}}
  <section class="hero">
    <div class="slick" id="hero-slick">
      @php $slideItems = $slides->take(5); @endphp
      @forelse($slideItems as $i => $s)
        <div class="item {{ $i === 0 ? 'is-active' : '' }}">
          <img src="{{ $s->image_url ?? $placeholder }}" alt="{{ $s->title ?? 'Slide' }}">
        </div>
      @empty
        <div class="item is-active"><img src="{{ $placeholder }}" alt="placeholder"></div>
      @endforelse
    </div>

    <div class="overlay"></div>

    <div class="caption">
      <div class="pill"><span class="dot-pill"></span> Bina Bordir • Crafting Batik & Kebaya</div>
    </div>

    <button class="nav-btn nav-prev" type="button" aria-label="Previous">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button class="nav-btn nav-next" type="button" aria-label="Next">
      <i class="fa-solid fa-chevron-right"></i>
    </button>

    <div class="dots" id="hero-dots">
      @for($i=0; $i < max(1, $slideItems->count()); $i++)
        <span class="dot {{ $i===0 ? 'is-active' : '' }}" data-index="{{ $i }}"></span>
      @endfor
    </div>
  </section>

  {{-- ===== GALLERY ===== --}}
  <section id="section-gallery" class="section">
    <div class="container-narrow">
      <div class="title"><i class="fa-solid fa-images"></i><h2>Our Gallery</h2></div>
      <p class="subtitle">A glimpse of our works & activities.</p>

      <div class="strip">
        @forelse($galleries->take(12) as $g)
          @php
            $isVideo = method_exists($g,'getIsVideoAttribute') ? $g->is_video : false;
            $src     = method_exists($g,'getUrlAttribute') ? $g->url : ($g->image ? asset('storage/'.$g->image) : $placeholder);
            $poster  = method_exists($g,'getPosterUrlAttribute') ? $g->poster_url : $placeholder;
            $mime    = method_exists($g,'getMimeTypeAttribute') ? $g->mime_type : 'video/mp4';
          @endphp
          <div class="g-thumb">
            <div class="frame">
              @if($isVideo)
                <video class="g-media is-video"
                       autoplay muted loop playsinline
                       preload="metadata"
                       poster="{{ $poster }}">
                  <source src="{{ $src }}" type="{{ $mime }}">
                </video>
              @else
                <img class="g-media" src="{{ $src }}" alt="{{ $g->title ?? 'Gallery' }}">
              @endif
            </div>
          </div>
        @empty
          @for($i=0;$i<6;$i++)
            <div class="g-thumb"><div class="frame"><img class="g-media" src="{{ $placeholder }}" alt="placeholder"></div></div>
          @endfor
        @endforelse
      </div>

      <div class="mt-3 d-flex justify-content-center">
        <a href="{{ $toGallery }}" class="btn btn-cta">See More</a>
      </div>
    </div>
  </section>

{{-- ===== PRODUCTS (match struktur Our Gallery) ===== --}}
<section id="section-products" class="section">
  <div class="container-narrow">
    <div class="title"><i class="fa-solid fa-bag-shopping"></i><h2>Our Products</h2></div>
    <p class="subtitle">Fresh collections — ready to ship across Indonesia.</p>

    @php $placeholder = asset('images/placeholder.webp'); @endphp

    <div class="bb-scroller-wrap">
      <div id="prodScroll" class="bb-scroller hide-scrollbar">
        @foreach($products as $p)
          @php
            $img      = $p->image_url ?: $placeholder;
            $toDetail = Route::has('product.detail') ? route('product.detail', $p->id) : '#';
            $name     = Str::limit($p->name ?? 'Product', 40);
            $price    = isset($p->price) ? number_format((float)$p->price, 0, ',', '.') : null;
          @endphp

          <a href="{{ $toDetail }}" class="card bb-prod-card text-decoration-none">
            <img src="{{ $img }}" alt="{{ $name }}" loading="lazy"
                 onerror="this.onerror=null;this.src='{{ $placeholder }}';">
            <div class="p-2">
              <div class="fw-semibold text-dark small mb-1">{{ $name }}</div>
              @if(!is_null($price))
                <div class="badge bg-warning text-dark">Rp {{ $price }}</div>
              @endif
            </div>
          </a>
        @endforeach
      </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
      <a href="{{ $toCatalog ?? (Route::has('catalogue') ? route('catalogue') : url('/catalogue')) }}"
         class="btn btn-cta">See More</a>
    </div>
  </div>
</section>

 {{-- ===== NEWS ===== --}}
@if($newsItems->isNotEmpty())
<section class="section">
  <div class="container-news">
    <div class="title"><i class="fa-solid fa-newspaper"></i><h2>Latest News</h2></div>
    <p class="subtitle">Updates and stories from Bina Bordir.</p>

    <div class="news-list">
      @foreach($newsItems->take(3) as $n)
        @php
            $nImg    = $n->image_url ?: ($n->image ? asset('storage/'.$n->image) : $placeholder);
            $rawBody = $n->body ?? $n->content ?? '';
            $excerpt = Str::limit(strip_tags($rawBody), 150);
        @endphp
        <article class="news-card">
          <a href="{{ $toNews }}" class="news-thumb text-decoration-none">
            <img src="{{ $nImg }}" alt="{{ $n->title ?? 'News' }}">
          </a>
          <div class="news-body">
            <a href="{{ $toNews }}" class="text-decoration-none">
              <h3 class="news-title">{{ Str::limit($n->title ?? 'Untitled', 90) }}</h3>
              <div class="news-meta">{{ optional($n->published_at ?? $n->created_at)->format('d M Y') }}</div>
            <div class="news-meta">{{ optional($n->published_at ?? $n->created_at)->format('d M Y') }}</div>
            @if(!empty($excerpt))
              <a href="{{ $toNews }}" class="text-decoration-none text-reset">
                <div class="news-excerpt">{{ $excerpt }}</div>
              </a>
            @endif
          </div>
        </article>
      @endforeach
    </div>

    <div class="mt-3 d-flex justify-content-center">
      <a href="{{ $toNews }}" class="btn btn-cta">
        <i class="fa-solid fa-newspaper"></i> View All News
      </a>
    </div>
  </div>
</section>
@endif



  {{-- ===== ABOUT ===== --}}
  <section id="section-about" class="section">
    <div class="container-narrow">
      <div class="title"><i class="fa-solid fa-circle-info"></i><h2>About Us</h2></div>

      <div class="about-card" style="max-width:980px;margin:0 auto;">
        <div class="about-wrap">
          <div>
            <div class="about-title">
              <span class="about-pill"><i class="fa-solid fa-seedling"></i> Since 2013</span>
              <h3>Bina Bordir</h3>
            </div>
            <p class="about-p">
              We develope embroidery & batik with a modern touch while preserving tradition.
              Based in Indonesia, we handle single orders to large-scale production with strict quality control.
            </p>
            <div class="about-badges" style="margin-top:.7rem">
              <span class="badge-chip"><i class="fa-solid fa-star"></i>&nbsp; Handmade</span>
              <span class="badge-chip"><i class="fa-solid fa-truck-fast"></i>&nbsp; Ready to Ship</span>
              <span class="badge-chip"><i class="fa-solid fa-leaf"></i>&nbsp; Local Craft</span>
            </div>
            <div style="margin-top:1rem; display:flex; gap:.6rem; flex-wrap:wrap;">
              <a href="{{ $toAbout }}" class="btn-cta"><i class="fa-solid fa-arrow-right"></i> Read More</a>
              <a href="{{ url('/contact') }}" class="btn-cta"><i class="fa-solid fa-phone"></i> Contact</a>
            </div>
          </div>

          <div>
            <ul class="about-list">
              <li><i class="fa-solid fa-circle-check"></i> Custom kebaya & apparel</li>
              <li><i class="fa-solid fa-circle-check"></i> Uniforms for institutions & communities</li>
              <li><i class="fa-solid fa-circle-check"></i> Embroidered souvenirs & accessories</li>
              <li><i class="fa-solid fa-circle-check"></i> Design & material consultation</li>
            </ul>
            <div style="margin-top:1rem; background:rgba(0,0,0,.04); padding:.9rem 1rem; border-radius:14px; border:1px solid rgba(0,0,0,.06);">
              <strong>Advantages:</strong>
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:.5rem; margin-top:.5rem;">
                <span class="badge-chip">Quality Control</span>
                <span class="badge-chip">Fast Delivery</span>
                <span class="badge-chip">Transparent Pricing</span>
                <span class="badge-chip">After-Sales Support</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

</div>

{{-- ==== SLIDER JS ==== --}}
<script>
(function(){
  const wrap = document.getElementById('hero-slick');
  if(!wrap) return;

  const slides = Array.from(wrap.querySelectorAll('.item'));
  if(slides.length === 0) return;

  const dotsWrap = document.getElementById('hero-dots');
  const dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('.dot')) : [];

  const prevBtn = document.querySelector('.hero .nav-prev');
  const nextBtn = document.querySelector('.hero .nav-next');
  let idx = 0;
  let autoplay = true;
  const intervalMs = 4000;
  let timer = null;

  function go(to){
    idx = (to + slides.length) % slides.length;
    slides.forEach((el, i) => el.classList.toggle('is-active', i === idx));
    dots.forEach((d, i) => d.classList.toggle('is-active', i === idx));
  }

  function next(){ go(idx + 1); }
  function prev(){ go(idx - 1); }

  function start(){ if(timer) clearInterval(timer); timer = setInterval(() => { if(autoplay) next(); }, intervalMs); }
  function pauseOnce(){ autoplay = false; setTimeout(() => autoplay = true, intervalMs * 1.5); }

  nextBtn && nextBtn.addEventListener('click', () => { pauseOnce(); next(); });
  prevBtn && prevBtn.addEventListener('click', () => { pauseOnce(); prev(); });

  dots.forEach(d => d.addEventListener('click', (e) => {
    const to = parseInt(e.currentTarget.getAttribute('data-index') || '0', 10);
    pauseOnce(); go(to);
  }));

  const hero = document.querySelector('.hero');
  hero && hero.addEventListener('mouseenter', () => autoplay = false);
  hero && hero.addEventListener('mouseleave', () => autoplay = true);

  window.addEventListener('keydown', (e) => {
    if(e.key === 'ArrowRight'){ pauseOnce(); next(); }
    if(e.key === 'ArrowLeft'){ pauseOnce(); prev(); }
  });

  start();
})();
</script>

{{-- ==== GALLERY VIDEO AUTOPLAY ==== --}}
<script>
(function(){
  const videos = document.querySelectorAll('.strip .is-video');
  if(!('IntersectionObserver' in window) || videos.length === 0){
    videos.forEach(v => { v.muted = true; v.setAttribute('playsinline',''); v.play().catch(()=>{}); });
    return;
  }

  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const v = entry.target;
      if(entry.isIntersecting && entry.intersectionRatio >= 0.6){
        v.muted = true;
        v.setAttribute('playsinline','');
        v.play().catch(()=>{});
      } else {
        v.pause();
      }
    });
  }, { threshold: [0, 0.6, 1] });

  videos.forEach(v => { v.muted = true; v.autoplay = true; v.loop = true; v.setAttribute('playsinline',''); io.observe(v); });

  document.addEventListener('visibilitychange', () => {
    if(document.hidden){ videos.forEach(v => v.pause()); }
    else {
      videos.forEach(v => {
        const rect = v.getBoundingClientRect();
        const visible = rect.width>0 && rect.height>0 && rect.top<innerHeight && rect.bottom>0;
        if(visible){ v.play().catch(()=>{}); }
      });
    }
  });
})();
</script>
@endsection
