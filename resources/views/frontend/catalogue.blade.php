@extends('layouts.frontend')
@section('title','Catalogue')

@section('content')
@php
  /** Guard variabel */
  $products    = $products ?? collect();
  $categories  = $categories ?? collect();
  $q           = $q ?? '';
  $categoryId  = $categoryId ?? null;
  $placeholder = asset('images/placeholder.webp');

  $hasMore     = method_exists($products, 'hasMorePages') ? $products->hasMorePages() : false;
  $hideLoader  = $hasMore ? '' : 'd-none';
  $hideEnd     = $hasMore ? 'd-none' : '';
@endphp

<style>
  :root{ --grad-start:#de4312; --grad-end:#fec163; --brand:#ff7b00; --brand-rgb:255,123,0; --text:#1b1b1b; }
  body{ background:linear-gradient(90deg,var(--grad-start) 0%,var(--grad-end) 100%); background-attachment:fixed; color:#fff; }
  .container-narrow{ max-width:1100px; margin-inline:auto; padding:0 1rem; }
  .section{ padding:56px 0; }
  .title{ display:flex; align-items:center; gap:.6rem; margin-bottom:14px; }
  .title h2{ margin:0; font-weight:900; color:#fff; letter-spacing:.2px; text-shadow:0 1px 0 rgba(0,0,0,.2); }
  .subtitle{ color:rgba(255,255,255,.9); margin-bottom:22px; }
  .panel{ background:rgba(255,255,255,.96); color:#111; border:1px solid rgba(0,0,0,.06); border-radius:18px; box-shadow:0 18px 36px rgba(0,0,0,.22); }
  .btn-cta{ background:linear-gradient(90deg,#ff7b00,#ff9a3d); color:#fff!important; font-weight:900; padding:.66rem 1.2rem; border-radius:999px; border:none; box-shadow:0 14px 30px rgba(0,0,0,.25); transition:.2s; }
  .btn-cta:hover{ transform:translateY(-2px); opacity:.96; }
  .cat-wrapper{ position:relative; }
  @media (min-width: 992px){ .cat-sticky{ position:sticky; top:84px; } }
  .cat-list .list-group-item{ border:0; margin-bottom:8px; border-radius:12px; background:#fff; color:#111; box-shadow:0 6px 16px rgba(0,0,0,.08); }
  .cat-list .list-group-item.active{ background:linear-gradient(90deg,#ff7b00,#ff9a3d); color:#fff; }
  .filters .form-control{ border-radius:999px; padding:.6rem 1rem; border:1px solid rgba(0,0,0,.08); }
  .card{ border:1px solid rgba(0,0,0,.06); border-radius:16px; background:#fff; color:var(--text); box-shadow:0 18px 36px rgba(0,0,0,.22); overflow:hidden; transition:transform .15s ease, box-shadow .15s ease; }
  .card:hover{ transform:translateY(-4px); box-shadow:0 24px 46px rgba(0,0,0,.28); }
  #product-grid .card .media, #product-grid .card .thumb{ position:relative; width:100%; aspect-ratio:1/1; overflow:hidden; background:#f6f6f6; border-top-left-radius:16px; border-top-right-radius:16px; }
  #product-grid .card .media>img, #product-grid .card .thumb>img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; }
  #product-grid .card img{ width:100%; aspect-ratio:1/1; object-fit:cover; display:block; border-top-left-radius:16px; border-top-right-radius:16px; background:#f6f6f6; }
  #product-grid .card img[style*="height"]{ height:auto!important; }
  .card .badge{ position:absolute; left:10px; top:10px; background:rgba(0,0,0,.78); color:#fff; padding:.28rem .55rem; border-radius:8px; font-size:.8rem; }
  .card .price{ position:absolute; right:10px; bottom:10px; background:rgba(var(--brand-rgb),.98); color:#fff; padding:.38rem .6rem; border-radius:10px; font-weight:800; box-shadow:0 6px 12px rgba(var(--brand-rgb),.35); }
  .muted{ color:rgba(255,255,255,.9); }
</style>

<div class="page">
  <section class="section">
    <div class="container-narrow">

      <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
        <div class="title mb-2 mb-sm-0">
          <i class="fa-solid fa-store"></i><h2>Catalogue</h2>
        </div>
        <a href="{{ url('/') }}" class="btn-cta"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
      </div>
      <p class="subtitle">Explore our collection — choose a category and search, then scroll to load more.</p>

      <div class="row g-3">
        {{-- Sidebar kategori --}}
        <aside class="col-lg-3">
          <div class="cat-wrapper">
            <div class="panel p-3 cat-list cat-sticky">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fa-solid fa-list"></i><strong>Categories</strong>
              </div>
              <div class="list-group">
                <a href="{{ route('catalogue') }}" class="list-group-item {{ !$categoryId ? 'active' : '' }}">All Categories</a>
                @foreach($categories as $category)
                  <a href="{{ route('catalogue', array_filter(['category'=>$category->id,'q'=>$q])) }}"
                     class="list-group-item {{ (int)$categoryId === (int)$category->id ? 'active' : '' }}">
                    {{ $category->name }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </aside>

        {{-- Konten produk --}}
        <section class="col-lg-9">
          <div class="panel p-3 mb-3">
            <form action="{{ route('catalogue') }}" method="GET" class="row g-2 align-items-center filters" id="catalogue-filters">
              @if($categoryId)<input type="hidden" name="category" value="{{ $categoryId }}">@endif
              <div class="col-12 col-sm-9">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search products...">
              </div>
              <div class="col-12 col-sm-3 d-grid d-sm-block">
                <button class="btn-cta w-100" type="submit">Search</button>
              </div>
            </form>
          </div>

          {{-- Grid --}}
          <div id="product-grid"
               class="row g-3"
               data-current-page="{{ method_exists($products,'currentPage') ? $products->currentPage() : 1 }}"
               data-last-page="{{ method_exists($products,'lastPage') ? $products->lastPage() : 1 }}">
            {{-- pakai partial kalau ada, jika tidak ada pakai fallback di bawah --}}
            @includeIf('frontend.partials.catalogue-items', ['products'=>$products,'placeholder'=>$placeholder])

            @unless(View::exists('frontend.partials.catalogue-items'))
              {{-- Fallback langsung di sini --}}
              @forelse($products as $p)
                <div class="col-6 col-md-4 col-xl-3" data-item="product-card">
                  <div class="card h-100 position-relative">
                    <div class="thumb"><img src="{{ $p->image_url ?? $placeholder }}" alt="{{ $p->name }}"></div>
                    <div class="card-body">
                      <div class="fw-semibold mb-1">{{ $p->name }}</div>
                      @if(!is_null($p->price))
                        <div class="text-danger fw-bold">Rp {{ number_format($p->price,0,',','.') }}</div>
                      @endif
                    </div>
                    <div class="card-footer bg-transparent">
                      <a class="btn btn-sm btn-outline-secondary w-100" href="{{ route('product.detail',$p->id) }}">Detail</a>
                    </div>
                  </div>
                </div>
              @empty
                <div class="alert alert-warning">Belum ada produk.</div>
              @endforelse
            @endunless
          </div>

          {{-- Loader & End --}}
          <div id="infinite-loader" class="text-center my-3 {{ $hideLoader }}">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <div class="small muted mt-2">Loading...</div>
          </div>
          <div id="infinite-end" class="text-center my-3 muted {{ $hideEnd }}">
            You're all caught up 🎉
          </div>

          <div id="infinite-sentinel" style="height:1px;"></div>
        </section>
      </div>
    </div>
  </section>
</div>

{{-- Infinite Scroll --}}
<script>
(function(){
  const grid     = document.getElementById('product-grid');
  const loader   = document.getElementById('infinite-loader');
  const endLabel = document.getElementById('infinite-end');
  const sentinel = document.getElementById('infinite-sentinel');
  const form     = document.getElementById('catalogue-filters');

  if (!grid || !sentinel) return;

  let currentPage = Number(grid.dataset.currentPage || 1);
  const lastPage  = Number(grid.dataset.lastPage || 1);
  let loading     = false;

  if (!('IntersectionObserver' in window)) {
    sentinel.style.display = 'none';
    return;
  }

  const observer = new IntersectionObserver(async ([entry]) => {
    if (!entry || !entry.isIntersecting || loading) return;

    if (currentPage >= lastPage) {
      loader.classList.add('d-none');
      endLabel.classList.remove('d-none');
      observer.disconnect();
      return;
    }

    loading = true;
    loader.classList.remove('d-none');

    try {
      let params = form ? new URLSearchParams(new FormData(form))
                        : new URLSearchParams(window.location.search);
      params.set('page', String(currentPage + 1));

      const url  = "{{ route('catalogue') }}" + '?' + params.toString();
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
      const html = await res.text();

      const temp = document.createElement('div');
      temp.innerHTML = html.trim();

      // Ambil kartu dari partial jika ada
      let nodes = temp.querySelectorAll('[data-item="product-card"]');
      if (!nodes.length) {
        const gridFromResponse = temp.querySelector('#product-grid');
        if (gridFromResponse) nodes = gridFromResponse.querySelectorAll('[data-item="product-card"]');
      }

      nodes.forEach(n => grid.appendChild(n));
      currentPage += 1;

      if (currentPage >= lastPage) {
        loader.classList.add('d-none');
        endLabel.classList.remove('d-none');
        observer.disconnect();
      } else {
        loader.classList.add('d-none');
      }
    } catch(e) {
      console.error(e);
      loader.classList.add('d-none');
    } finally {
      loading = false;
    }
  }, { rootMargin: '400px 0px' });

  observer.observe(sentinel);
})();
</script>
@endsection
