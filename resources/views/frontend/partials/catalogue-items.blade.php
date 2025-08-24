@php
  $products    = $products ?? collect();
  $placeholder = $placeholder ?? asset('images/placeholder.webp');
@endphp

@forelse ($products as $p)
  <div class="col-6 col-md-4 col-xl-3" data-item="product-card">
    <div class="card h-100 position-relative">
      <div class="thumb">
        <img
          src="{{ $p->image_url ?? $placeholder }}"
          alt="{{ $p->name ?? 'Produk' }}"
          loading="lazy">
      </div>

      <div class="card-body">
        <div class="fw-semibold mb-1">{{ $p->name ?? 'Tanpa nama' }}</div>
        @if(isset($p->price) && $p->price !== null)
          <div class="text-danger fw-bold">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
        @endif
      </div>

      <div class="card-footer bg-transparent">
        @if(isset($p->id))
          <a class="btn btn-sm btn-outline-secondary w-100"
             href="{{ route('product.detail', $p->id) }}">Detail</a>
        @else
          <button class="btn btn-sm btn-outline-secondary w-100" disabled>Detail</button>
        @endif
      </div>
    </div>
  </div>
@empty
  <div class="col-12">
    <div class="alert alert-warning mb-0">Belum ada produk.</div>
  </div>
@endforelse
