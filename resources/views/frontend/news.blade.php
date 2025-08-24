@extends('layouts.frontend')
@section('title','News')

@section('content')
@php
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Str;

  $placeholder = $placeholder ?? asset('images/placeholder.webp');

  // Base URL aman (named route / fallback)
  $newsUrl = Route::has('news.index') ? route('news.index')
           : (Route::has('news') ? route('news') : url('/news'));

  // Featured strip: ambil dari paginator ->items() kalau ada, atau langsung collection
  $basePosts     = $posts ?? collect();
  $featuredPosts = collect(method_exists($basePosts, 'items') ? $basePosts->items() : $basePosts)->take(8);
@endphp

<style>
  :root{
    --grad-start:#de4312;
    --grad-end:#fec163;
    --brand:#ff7b00;
    --brand-rgb:255,123,0;
    --brand-dark:#b55600;

    /* Tipografi konsisten (fallback kalau font tak terpasang) */
    --font-head:'Sora', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    --font-body:'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  }
  body{
    background: linear-gradient(90deg, var(--grad-start) 0%, var(--grad-end) 100%);
    background-attachment: fixed;
  }
  .page{ padding-top:80px; color:#fff; }
  .container-narrow{ max-width:1100px; margin-inline:auto; padding:0 1rem; }
  .section{ padding:40px 0; }

  /* Title */
  .title{ display:flex; align-items:center; gap:.6rem; margin-bottom:14px; }
  .title h2{
    margin:0; color:#fff; text-shadow:0 1px 0 rgba(0,0,0,.2);
    font-family:var(--font-head); font-weight:800; letter-spacing:.2px;
    font-size: clamp(1.35rem, 1.1rem + 1.4vw, 2.25rem);
    line-height:1.1;
  }
  .subtitle{
    color:rgba(255,255,255,.92); margin-bottom:22px;
    font-family:var(--font-body); font-weight:500; letter-spacing:.2px;
    font-size: clamp(.98rem, .92rem + .25vw, 1.1rem);
  }

  /* Panel putih */
  .panel{
    background:rgba(255,255,255,.96);
    color:#111; border-radius:18px; border:1px solid rgba(0,0,0,.06);
    box-shadow:0 18px 36px rgba(0,0,0,.22); padding:1rem;
  }

  /* ===== FEATURED STRIP ===== */
  .hstrip-wrap{ position:relative; margin-bottom:18px; }
  .hstrip{
    display:grid; grid-auto-flow:column; grid-auto-columns:minmax(260px, 1fr);
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
  .hcard .media img{ width:100%; height:180px; object-fit:cover; display:block; }
  .hcard .body{ padding:.85rem .95rem 1rem; }
  .hcard .title{
    margin:0 0 .25rem; color:var(--brand);
    font-family:var(--font-head); font-weight:800; letter-spacing:.2px;
    font-size: clamp(1rem, .96rem + .35vw, 1.15rem);
    line-height:1.25;
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

  /* ===== LIST KARTU (thumbnail kiri, teks kanan) ===== */
  .list{ display:grid; gap:1rem; }
  .list-card{
    display:flex; gap:1rem; align-items:stretch;
    border:1px solid rgba(0,0,0,.06); border-radius:16px; overflow:hidden;
    background:#fff; color:#111; box-shadow:0 12px 28px rgba(0,0,0,.18);
    transition:transform .15s ease, box-shadow .15s ease;
  }
  .list-card:hover{ transform:translateY(-3px); box-shadow:0 20px 40px rgba(0,0,0,.23); }

  .thumb{ flex:0 0 160px; height:160px; overflow:hidden; background:#f6f6f6; display:block; }
  .thumb img{ width:100%; height:100%; object-fit:cover; display:block; }

  .lbody{ padding:0.95rem 1rem; flex:1 1 auto; display:flex; flex-direction:column; }
  .ltitle{
    margin:0; line-height:1.25;
    font-family:var(--font-head); font-weight:800; letter-spacing:.2px;
    font-size: clamp(1.05rem, .98rem + .5vw, 1.35rem);
    color:var(--brand);
  }
  .lmeta{
    font-size:.9rem; color:#6b7280; margin:.2rem 0 .5rem;
    font-family:var(--font-body);
  }
  .lexcerpt{
    font-family:var(--font-body);
    font-size: clamp(.98rem, .95rem + .25vw, 1.05rem);
    color:#2b2b2b; line-height:1.6; letter-spacing:.2px;
    display:-webkit-box; -webkit-line-clamp:3; line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;
  }

  @media (max-width:576px){
    .thumb{ flex-basis:110px; height:110px; }
    .lbody{ padding:.8rem; }
  }

  .muted{ color:rgba(255,255,255,.9); }

  /* CTA */
  .btn-cta{
    background:linear-gradient(90deg,#ff7b00,#ff9a3d);
    color:#fff!important; font-weight:900;
    padding:.66rem 1.2rem; border-radius:999px;
    box-shadow:0 14px 30px rgba(0,0,0,.25);
    transition:.2s; white-space:nowrap;
    font-family:var(--font-head); letter-spacing:.2px;
  }
  .btn-cta:hover{ transform:translateY(-2px); opacity:.96; }
</style>

<div class="page">
  <section class="section">
    <div class="container-narrow">

      {{-- Header + Back --}}
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
        <div class="title mb-2 mb-sm-0">
          <i class="fa-solid fa-newspaper"></i><h2>Latest News</h2>
        </div>
        <a href="{{ url('/') }}" class="btn-cta">
          <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
      </div>
      <p class="subtitle">Latest updates and articles from Bina Bordir.</p>

      {{-- ===== Featured Slider (max 8) — TANPA LINK ===== --}}
      @if($featuredPosts->isNotEmpty())
        <div class="hstrip-wrap">
          <button class="hnav prev" type="button" data-target="#news-featured"><i class="fa-solid fa-chevron-left"></i></button>
          <div id="news-featured" class="hstrip">
            @foreach($featuredPosts as $n)
              @php
                $nImg = $n->image_url ?? ($n->image ? asset('storage/'.$n->image) : $placeholder);
              @endphp
              <article class="hcard" aria-label="Featured news item">
                <div class="media"><img src="{{ $nImg }}" alt="{{ $n->title ?? 'News' }}"></div>
                <div class="body">
                  <h4 class="title">{{ Str::limit($n->title ?? 'Untitled', 70) }}</h4>
                  <div class="meta">{{ optional($n->published_at ?? $n->created_at)->format('d M Y') }}</div>
                </div>
              </article>
            @endforeach
          </div>
          <button class="hnav next" type="button" data-target="#news-featured"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
      @endif

      {{-- ===== LIST BAWAH (tanpa link) ===== --}}
      @if(isset($posts) && $posts->count())
        <div class="panel">
          <div id="news-list"
               class="list"
               data-base-url="{{ $newsUrl }}"
               data-current-page="{{ $posts->currentPage() }}"
               data-last-page="{{ $posts->lastPage() }}">
            @foreach($posts as $n)
              @php
                $nImg    = $n->image_url ?? ($n->image ? asset('storage/'.$n->image) : $placeholder);
                $rawBody = $n->body ?? $n->content ?? '';
                $excerpt = Str::limit(strip_tags($rawBody), 160);
              @endphp
              <article class="list-card" data-item="news-card" aria-label="News item">
                <div class="thumb"><img src="{{ $nImg }}" alt="{{ $n->title ?? 'News' }}"></div>
                <div class="lbody">
                  <h3 class="ltitle">{{ Str::limit($n->title ?? 'Untitled', 90) }}</h3>
                  <div class="lmeta">{{ optional($n->published_at ?? $n->created_at)->format('d M Y') }}</div>
                  @if(!empty($excerpt))
                    <div class="lexcerpt">{{ $excerpt }}</div>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        </div>

        <div id="news-loader" class="text-center my-3 {{ $posts->hasMorePages() ? '' : 'd-none' }}">
          <div class="spinner-border" role="status"></div>
          <div class="small muted mt-2">Loading...</div>
        </div>
        <div id="news-end" class="text-center my-3 muted {{ $posts->hasMorePages() ? 'd-none' : '' }}">
          You're all caught up 🎉
        </div>
        <div id="news-sentinel" style="height:1px;"></div>
      @else
        <div class="panel text-center">No articles available.</div>
      @endif
    </div>
  </section>
</div>

{{-- ==== JS: Featured Strip Nav + Infinite Scroll (layout list) ==== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Strip nav
  document.querySelectorAll('.hnav').forEach((btn)=>{
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
  });

  // Infinite scroll (append elemen .list-card)
  const list     = document.getElementById('news-list');
  const loader   = document.getElementById('news-loader');
  const endLabel = document.getElementById('news-end');
  const sentinel = document.getElementById('news-sentinel');
  if (!list || !('IntersectionObserver' in window)) return;

  let currentPage = Number(list.dataset.currentPage || 1);
  const lastPage  = Number(list.dataset.lastPage || 1);
  let loading     = false;
  const baseUrl   = list.dataset.baseUrl;

  const observer  = new IntersectionObserver(async (entries) => {
    if (!entries[0].isIntersecting || loading) return;

    if (currentPage >= lastPage) {
      loader?.classList.add('d-none');
      endLabel?.classList.remove('d-none');
      observer.disconnect(); return;
    }

    loading = true; loader?.classList.remove('d-none');

    const nextPage = currentPage + 1;
    const urls = [
      `${baseUrl}?append=posts&page=${nextPage}`,
      `${baseUrl}?page=${nextPage}`
    ];

    let appended = false;
    for (const url of urls) {
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
        const html = await res.text();
        const temp = document.createElement('div');
        temp.innerHTML = html.trim();
        const items = temp.querySelectorAll('[data-item="news-card"]');
        if (items.length) {
          items.forEach(c => list.appendChild(c));
          appended = true;
          break;
        }
      } catch (e) { /* ignore */ }
    }

    if (appended) {
      currentPage++;
      if (currentPage >= lastPage) {
        loader?.classList.add('d-none'); endLabel?.classList.remove('d-none');
        observer.disconnect();
      } else {
        loader?.classList.add('d-none');
      }
    } else {
      loader?.classList.add('d-none');
      observer.disconnect();
    }
    loading = false;
  }, { rootMargin: '400px 0px' });

  observer.observe(sentinel);
});
</script>
@endsection
