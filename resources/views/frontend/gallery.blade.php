@extends('layouts.frontend')
@section('title','Gallery')

@section('content')
@php
  use Illuminate\Support\Arr;

  $placeholder = asset('images/placeholder.webp');

  // Ambil max 12 item buat strip atas (support paginator & collection)
  $base = $galleries ?? collect();
  $featuredGals = collect(method_exists($base, 'items') ? $base->items() : $base)->take(12);
@endphp

<style>
  :root{
    --grad-start:#de4312;
    --grad-end:#fec163;
    --brand:#ff7b00;
    --brand-rgb:255,123,0;
    --text:#1b1b1b;

    /* Tipografi konsisten */
    --font-head:'Sora', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    --font-body:'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  }
  body{
    background: linear-gradient(90deg, var(--grad-start) 0%, var(--grad-end) 100%);
    background-attachment: fixed;
    color:#fff;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  .container-narrow{ max-width:1100px; margin-inline:auto; padding:0 1rem; }
  .section{ padding:56px 0; }
  .title{ display:flex; align-items:center; gap:.6rem; margin:0; }
  .title h2{
    margin:0; color:#fff; text-shadow:0 1px 0 rgba(0,0,0,.2);
    font-family:var(--font-head); font-weight:800; letter-spacing:.2px;
    font-size: clamp(1.35rem, 1.1rem + 1.4vw, 2.25rem);
    line-height:1.1;
  }
  .subtitle{
    color:rgba(255,255,255,.92); margin-top:10px; margin-bottom:22px;
    font-family:var(--font-body); font-weight:500; letter-spacing:.2px;
    font-size: clamp(.98rem, .92rem + .25vw, 1.1rem);
  }

  .btn, .btn:visited{ text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; }
  .btn-cta{
    background:linear-gradient(90deg,#ff7b00,#ff9a3d);
    color:#fff !important; font-weight:900;
    padding:.56rem 1rem; border-radius:999px; border:none;
    box-shadow:0 14px 30px rgba(0,0,0,.25); transition:.2s;
    white-space:nowrap; font-family:var(--font-head); letter-spacing:.2px;
  }
  .btn-cta:hover{ transform:translateY(-2px); opacity:.96; color:#fff!important; }

  /* ===== Featured horizontal strip only ===== */
  .hstrip-wrap{ position:relative; }
  .hstrip{
    display:grid; grid-auto-flow:column; grid-auto-columns:minmax(320px, 1fr);
    gap:1rem; overflow-x:auto; scroll-behavior:smooth; scroll-snap-type:x mandatory;
    padding-bottom:.25rem;
  }
  .hstrip::-webkit-scrollbar{ height:8px; }
  .hstrip::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.35); border-radius:99px; }

  .hcard{
    scroll-snap-align:start; background:#fff; color:#111; border-radius:16px;
    border:1px solid rgba(0,0,0,.06); box-shadow:0 14px 30px rgba(0,0,0,.22); overflow:hidden;
  }
  .hcard .media{ position:relative; background:#f6f6f6; }
  .hcard .media img,
  .hcard .media video{ width:100%; height:240px; object-fit:cover; display:block; }
  .badge-chip{
    position:absolute; right:10px; top:10px; background:rgba(0,0,0,.75); color:#fff;
    border-radius:8px; padding:.25rem .5rem; font-size:.8rem; font-weight:700; font-family:var(--font-head);
  }

  .hcard .body{ padding:.9rem .95rem 1rem; }
  .hcard .title{
    margin:0 0 .25rem; color:#111; line-height:1.35;
    font-family:var(--font-head); font-weight:800; letter-spacing:.2px;
    font-size: clamp(1rem, .96rem + .35vw, 1.15rem);
  }
  .hcard .meta{
    font-size:.9rem; color:#6b7280; font-family:var(--font-body); letter-spacing:.2px;
  }

  .hnav{
    position:absolute; top:50%; transform:translateY(-50%);
    width:42px; height:42px; border-radius:999px; border:none;
    background:rgba(255,255,255,.95); color:#111;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 8px 20px rgba(0,0,0,.25); cursor:pointer; z-index:2;
  }
  .hnav.prev{ left:-12px; }
  .hnav.next{ right:-12px; }
  @media(max-width: 768px){ .hnav.prev{ left:6px; } .hnav.next{ right:6px; } }
</style>

<div class="page">
  <section class="section">
    <div class="container-narrow">

      {{-- Header + Back --}}
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
        <div class="title">
          <i class="fa-solid fa-images"></i><h2>Bina Bordir Gallery</h2>
        </div>
        <a href="{{ url('/') }}" class="btn-cta mt-2 mt-sm-0">
          <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
      </div>
      <p class="subtitle">A collection of our works, processes, and activities.</p>

      {{-- ===== Featured Strip (ONLY) ===== --}}
      @if($featuredGals->isNotEmpty())
        <div class="hstrip-wrap">
          <button class="hnav prev" type="button" data-target="#gallery-featured"><i class="fa-solid fa-chevron-left"></i></button>
          <div id="gallery-featured" class="hstrip">
            @foreach($featuredGals as $g)
              @php
                $isVideo = method_exists($g,'getIsVideoAttribute') ? $g->is_video : false;
                $src     = method_exists($g,'getUrlAttribute') ? $g->url : ($g->image ? asset('storage/'.$g->image) : $placeholder);
                $poster  = method_exists($g,'getPosterUrlAttribute') ? $g->poster_url : $placeholder;
                $mime    = method_exists($g,'getMimeTypeAttribute') ? $g->mime_type : 'video/mp4';
              @endphp
              <article class="hcard">
                <div class="media">
                  @if($isVideo)
                    <video class="is-video"
                           autoplay
                           muted
                           loop
                           playsinline
                           preload="metadata"
                           poster="{{ $poster }}">
                      <source src="{{ $src }}" type="{{ $mime }}">
                    </video>
                    <span class="badge-chip">Video</span>
                  @else
                    <img src="{{ $src }}" alt="{{ $g->title ?? 'Gallery' }}">
                  @endif
                </div>
                <div class="body">
                  <h4 class="title">{{ \Illuminate\Support\Str::limit($g->title ?? 'Gallery', 60) }}</h4>
                  @if(!empty($g->created_at))
                    <div class="meta">{{ $g->created_at->format('d M Y') }}</div>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
          <button class="hnav next" type="button" data-target="#gallery-featured"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
      @else
        <div class="text-center" style="background:rgba(255,255,255,.96);color:#111;border:1px solid rgba(0,0,0,.06);border-radius:18px;box-shadow:0 18px 36px rgba(0,0,0,.22);padding:1rem;">
          No media added yet.
        </div>
      @endif

      {{-- Tidak ada grid bawah / infinite scroll sesuai request --}}
    </div>
  </section>
</div>

{{-- ==== JS: Strip Nav + Video Autoplay Guard (IO) ==== --}}
<script>
(function(){
  // Nav untuk strip
  function setupStripNav(btn){
    const targetSel = btn.getAttribute('data-target');
    const strip = document.querySelector(targetSel);
    if(!strip) return;
    const dir = btn.classList.contains('prev') ? -1 : 1;
    btn.addEventListener('click', () => {
      const first = strip.querySelector(':scope > *');
      const gap = parseFloat(getComputedStyle(strip).columnGap || '16');
      const step = first ? first.getBoundingClientRect().width + gap : strip.clientWidth * 0.9;
      strip.scrollBy({ left: dir * step, behavior: 'smooth' });
    });
  }
  document.querySelectorAll('.hnav').forEach(setupStripNav);

  // Autoplay video: play hanya saat terlihat (hemat resource)
  const videos = document.querySelectorAll('#gallery-featured .is-video');
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
  }, { threshold: [0, .6, 1] });
  videos.forEach(v => { v.muted = true; v.loop = true; v.autoplay = true; v.setAttribute('playsinline',''); io.observe(v); });

  // Pause saat tab tidak aktif
  document.addEventListener('visibilitychange', () => {
    if(document.hidden){ videos.forEach(v => v.pause()); }
    else {
      videos.forEach(v => {
        const rect = v.getBoundingClientRect();
        const visible = rect.width>0 && rect.height>0 && rect.top<innerHeight && rect.bottom>0;
        if(visible) v.play().catch(()=>{});
      });
    }
  });
})();
</script>
@endsection
