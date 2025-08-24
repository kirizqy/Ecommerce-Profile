@extends('layouts.frontend')
@section('title', $product->name ?? 'Product Detail')

@section('content')
@php
  use Illuminate\Support\Str;

  /* ========= MEDIA (foto & video, max 8 item) ========= */
  // FOTO dari controller: $images = $product->images_urls (URL siap pakai)
  $images = array_values(array_filter($images ?? [], fn ($u) => !empty($u)));

  // VIDEO (opsional)
  $videosRaw = [];
  if (!empty($product->video))        $videosRaw[] = $product->video;
  if (!empty($product->video_url))    $videosRaw[] = $product->video_url;
  if (!empty($product->videos) && is_iterable($product->videos)) {
    foreach ($product->videos as $v) { $videosRaw[] = $v; }
  }

  $videos = [];
  foreach ($videosRaw as $v) {
    if (!$v) continue;
    $videos[] = Str::startsWith($v, ['http://','https://']) ? $v : asset('storage/'.$v);
  }

  // Gabungkan foto lalu video (maks 8)
  $media = collect();
  foreach ($images as $u) { $media->push(['type'=>'image','src'=>$u]); }
  foreach ($videos as $u) { $media->push(['type'=>'video','src'=>$u]); }
  $media = $media->take(8)->values();

  // Placeholder jika kosong
  $placeholder = asset('images/placeholder.webp');
  if ($media->isEmpty()) {
    $media->push(['type'=>'image','src'=>$placeholder]);
  }

  /* ========= Link marketplace & WA ========= */
  $shopee = trim($product->shopee_link ?? $product->shopee_url ?? '');
  $tokped = trim($product->tokopedia_link ?? $product->tokopedia_url ?? '');

  $waRaw = $product->whatsapp_link
        ?? $product->whatsapp_url
        ?? $product->whatsapp_number
        ?? $product->whatsapp
        ?? null;

  $waLink = null;
  if (!empty($waRaw)) {
    $waText = 'Halo, apakah produk *'.$product->name.'* masih tersedia?';
    if (Str::startsWith($waRaw, ['http://','https://','wa.me','api.whatsapp.com'])) {
      $waLink = (Str::startsWith($waRaw, ['http://','https://'])) ? $waRaw : 'https://'.$waRaw;
      $waLink .= (Str::contains($waLink,'?') ? '&' : '?').'text='.urlencode($waText);
    } else {
      $digits = preg_replace('/\D+/', '', (string)$waRaw);
      if ($digits) {
        if (Str::startsWith($digits, '0')) $digits = '62'.substr($digits,1);
        if (Str::startsWith($digits, '8')) $digits = '62'.$digits;
        $waLink = 'https://wa.me/'.$digits.'?text='.urlencode($waText);
      }
    }
  }
@endphp

<style>
  :root{
    --grad-start:#de4312; --grad-end:#fec163;
    --brand:#ff7b00; --brand-rgb:255,123,0; --brand-dark:#b55600; --text:#1b1b1b;

    /* Tipografi konsisten dengan page lain */
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

  .title{ display:flex; align-items:center; gap:.6rem; margin-bottom:14px; }
  .title h2{
    margin:0; letter-spacing:.2px; text-shadow:0 1px 0 rgba(0,0,0,.2);
    font-family:var(--font-head); font-weight:800; color:#fff;
    font-size: clamp(1.35rem, 1.1rem + 1.4vw, 2.25rem);
    line-height:1.1;
  }
  .subtitle{
    color:rgba(255,255,255,.9); margin-bottom:22px;
    font-family:var(--font-body); font-weight:500; letter-spacing:.2px;
    font-size: clamp(.98rem, .92rem + .25vw, 1.1rem);
  }

  .panel{
    background:rgba(255,255,255,.96); color:#111; border:1px solid rgba(0,0,0,.06);
    border-radius:20px; box-shadow:0 18px 36px rgba(0,0,0,.22); padding:1.2rem;
  }

  /* ===== MEDIA SLIDER ===== */
  .pmedia{ position:relative; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 14px 30px rgba(0,0,0,.12); }
  .pmedia-main{ position:relative; aspect-ratio:1/1; background:#f6f6f6; }
  .pmedia-main img, .pmedia-main video{ width:100%; height:100%; object-fit:cover; display:block; }
  @media (min-width: 992px){ .pmedia-main{ aspect-ratio:4/5; } }

  .pmedia-nav{ position:absolute; inset-block:0; inset-inline:0; display:flex; justify-content:space-between; align-items:center; padding:0 .5rem; pointer-events:none; }
  .pmedia-btn{
    pointer-events:auto; width:42px; height:42px; border:0; border-radius:999px; background:#fff; color:#111;
    display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(0,0,0,.2); cursor:pointer; opacity:.95;
  }

  .pmedia-thumbs{ display:grid; grid-auto-flow:column; grid-auto-columns:96px; gap:.6rem; overflow-x:auto; padding:.6rem; background:#fff; }
  .pmedia-thumbs::-webkit-scrollbar{ height:8px }
  .pmedia-thumbs::-webkit-scrollbar-thumb{ background:rgba(0,0,0,.15); border-radius:99px }
  .pmedia-thumb{ width:96px; height:96px; border-radius:12px; overflow:hidden; background:#f2f2f2; border:2px solid transparent; cursor:pointer; }
  .pmedia-thumb img, .pmedia-thumb video{ width:100%; height:100%; object-fit:cover; display:block; }
  .pmedia-thumb.is-active{ border-color:#ff7b00; }

  .chip{
    display:inline-flex; align-items:center; gap:.35rem; background:#fff; color:#111;
    border:1px solid rgba(0,0,0,.06); border-radius:999px; padding:.3rem .6rem; font-weight:700; font-size:.9rem; box-shadow:0 6px 14px rgba(0,0,0,.12);
    font-family:var(--font-head);
  }

  /* ===== TIPOGRAFI DETAIL ===== */
  .p-name{
    margin:0 0 .35rem;
    font-family:var(--font-head); font-weight:900; letter-spacing:.2px; line-height:1.15;
    font-size: clamp(1.25rem, 1.05rem + 1.2vw, 2rem);
    /* aksen brand */
    background: linear-gradient(90deg, var(--brand), #ff9a3d);
    -webkit-background-clip: text; background-clip: text; color: transparent;
  }

  .price{
    font-family:var(--font-head); font-weight:900; color:#111;
    font-size: clamp(1.1rem, 1rem + .9vw, 1.8rem);
  }

  .desc-card{ background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:14px; padding:1rem 1.1rem; box-shadow:0 10px 24px rgba(0,0,0,.12); }
  .desc-title{
    font-family:var(--font-head); font-weight:900; margin-bottom:.45rem; letter-spacing:.2px; color:#111;
    font-size: clamp(1rem, .95rem + .4vw, 1.25rem);
  }
  .desc-body{
    font-family:var(--font-body); color:#2b2b2b; line-height:1.7; letter-spacing:.2px;
    font-size: clamp(1rem, .96rem + .3vw, 1.12rem);
  }
  .desc-empty{ color:#8b8b8b; font-style:italic; }

  /* ===== Buttons ===== */
  .btn, .btn:visited{ text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; }
  .btn-cta{
    background:linear-gradient(90deg,#ff7b00,#ff9a3d); color:#fff !important; font-weight:900;
    padding:.66rem 1.2rem; border-radius:999px; border:none; box-shadow:0 14px 30px rgba(0,0,0,.25); transition:.2s;
    font-family:var(--font-head); letter-spacing:.2px;
  }
  .btn-cta:hover{ transform:translateY(-2px); opacity:.96; color:#fff!important; }

  .btn-brand{ color:#fff!important; border:none; font-family:var(--font-head); font-weight:800; }
  .btn-shopee{ background:#ff6600; } .btn-tokopedia{ background:#18a64a; } .btn-whatsapp{ background:#25D366; }
  .btn-brand:hover{ filter:brightness(.95); }

  .sp-1{ height:8px; }
</style>

<div class="page">
  <section class="section">
    <div class="container-narrow">

      <div class="title"><i class="fa-solid fa-tag"></i><h2>Product Detail</h2></div>
      <p class="subtitle">Detail produk dengan galeri, informasi, dan tautan pembelian.</p>

      <div class="panel">
        <div class="row g-4 align-items-start">

          {{-- KIRI: Media slider --}}
          <div class="col-lg-6">
            <div id="pmedia" class="pmedia">
              <div class="pmedia-main" data-index="0">
                @php $first = $media->first(); @endphp
                @if(($first['type'] ?? 'image') === 'video')
                  <video src="{{ $first['src'] }}" playsinline controls aria-label="Video produk"></video>
                @else
                  <img src="{{ $first['src'] }}" alt="{{ $product->name }}" aria-label="Gambar produk">
                @endif

                <div class="pmedia-nav">
                  <button class="pmedia-btn" type="button" data-act="prev" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                  </button>
                  <button class="pmedia-btn" type="button" data-act="next" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                  </button>
                </div>
              </div>

              <div class="pmedia-thumbs">
                @foreach($media as $i => $m)
                  <div class="pmedia-thumb {{ $i===0 ? 'is-active' : '' }}" data-index="{{ $i }}">
                    @if($m['type'] === 'video')
                      <video src="{{ $m['src'] }}" aria-label="Thumbnail video {{ $i+1 }}"></video>
                    @else
                      <img src="{{ $m['src'] }}" alt="Thumbnail {{ $i+1 }}">
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- KANAN: Info produk --}}
          <div class="col-lg-6">
            <h1 class="p-name">{{ $product->name }}</h1>

            <div class="d-flex flex-wrap gap-2 mb-2">
              @if(!empty($product->category?->name))
                <span class="chip"><i class="fa-solid fa-folder"></i> {{ $product->category->name }}</span>
              @endif
              @isset($product->stock)
                <span class="chip"><i class="fa-solid fa-box"></i> Stok: {{ $product->stock }}</span>
              @endisset
            </div>

            @isset($product->price)
              <div class="price mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            @endisset

            @if($shopee || $tokped || $waLink)
              <div class="mt-3">
                <div class="small text-muted mb-1" style="color:#4b5563!important;font-family:var(--font-body)">Beli Produk Ini :</div>
                <div class="d-flex flex-wrap gap-2">
                  @if($shopee)
                    <a href="{{ $shopee }}" target="_blank" rel="noopener nofollow" class="btn btn-sm btn-brand btn-shopee">
                      <i class="fa-brands fa-shopify" style="width:14px;"></i> Shopee
                    </a>
                  @endif
                  @if($tokped)
                    <a href="{{ $tokped }}" target="_blank" rel="noopener nofollow" class="btn btn-sm btn-brand btn-tokopedia">
                      <i class="fa-solid fa-store" style="width:14px;"></i> Tokopedia
                    </a>
                  @endif
                  @if($waLink)
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-sm btn-brand btn-whatsapp">
                      <i class="fa-brands fa-whatsapp" style="width:14px;"></i> WhatsApp
                    </a>
                  @endif
                </div>
              </div>
            @endif

            <div class="sp-1"></div>

            <div class="desc-card">
              <div class="desc-title"><i class="fa-solid fa-circle-info"></i> Deskripsi</div>
              <div class="desc-body">
                {!! !empty($product->description)
                      ? nl2br(e($product->description))
                      : '<span class="desc-empty">Belum ada deskripsi.</span>' !!}
              </div>
            </div>

            <div class="mt-3 d-flex gap-2">
              <a href="{{ url()->previous() ?: url('/') }}" class="btn-cta"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
              <a href="{{ url('/catalogue') }}" class="btn-cta"><i class="fa-solid fa-store"></i> Lihat Katalog</a>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</div>

@push('body')
<script>
(function(){
  const wrap   = document.getElementById('pmedia');
  if(!wrap) return;

  const main   = wrap.querySelector('.pmedia-main');
  const thumbs = Array.from(wrap.querySelectorAll('.pmedia-thumb'));
  const prev   = wrap.querySelector('[data-act="prev"]');
  const next   = wrap.querySelector('[data-act="next"]');

  if(thumbs.length === 0) return;

  const items = thumbs.map(t => {
    const v = t.querySelector('video');
    const i = t.querySelector('img');
    return { type: v ? 'video' : 'image', src: (v ? v.src : i.src) };
  });

  let idx = 0;

  function render(i){
    idx = (i + items.length) % items.length;
    const it = items[idx];

    main.querySelectorAll('img,video').forEach(n => n.remove());

    if(it.type === 'video'){
      const v = document.createElement('video');
      v.src = it.src; v.controls = true; v.playsInline = true;
      v.setAttribute('aria-label','Video produk');
      main.prepend(v);
    } else {
      const img = document.createElement('img');
      img.src = it.src; img.alt = 'Gambar produk';
      main.prepend(img);
    }

    thumbs.forEach((t,ti) => t.classList.toggle('is-active', ti === idx));
  }

  thumbs.forEach(t => t.addEventListener('click', () => {
    const i = parseInt(t.getAttribute('data-index') || '0', 10);
    render(i);
  }));

  prev && prev.addEventListener('click', () => render(idx - 1));
  next && next.addEventListener('click', () => render(idx + 1));
})();
</script>
@endpush
@endsection
