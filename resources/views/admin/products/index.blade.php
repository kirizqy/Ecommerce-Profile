@extends('layouts.admin')

@php
  $isCatalogue = request()->routeIs('admin.catalogue.*');
@endphp

@section('title', $isCatalogue ? 'Katalog' : 'Produk')

@push('styles')
<style>
  .bb-chip-title{
    background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block
  }
  .table>:not(caption)>*>*{padding:.6rem .8rem}
  .table thead th{font-weight:600}
  .prod-thumb{width:110px;height:110px;object-fit:cover;border-radius:6px}
  .thumb-wrap{position:relative;display:inline-block}
  .thumb-wrap .badge-count{
    position:absolute;top:2px;right:2px;
    background:#212529;color:#fff;border-radius:.5rem;
    padding:.15rem .4rem;font-size:.7rem;font-weight:600;
  }
</style>
@endpush

@section('header')
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div><span class="bb-chip-title">{{ $isCatalogue ? 'Katalog' : 'Produk' }}</span></div>
    @unless($isCatalogue)
      <div class="d-flex gap-2 ms-auto">
        @if (Route::has('admin.products.create'))
          <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Produk
          </a>
        @endif
      </div>
    @endunless
  </div>
@endsection

@section('content')
  <div class="container-fluid">

    {{-- Flash success --}}
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- Filter --}}
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama produk...">
          </div>
          <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
              <option value="">-- Semua Kategori --</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected((string)request('category_id') === (string)$cat->id)>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-outline-secondary">
              <i class="fas fa-filter me-1"></i> Filter
            </button>
            <a href="{{ $isCatalogue ? route('admin.catalogue.index') : route('admin.products.index') }}"
               class="btn btn-outline-light border">
              <i class="fas fa-rotate-left me-1"></i> Reset
            </a>
          </div>
        </form>
      </div>
    </div>

    {{-- Tabel --}}
    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:72px">Gambar</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th class="text-end">Harga</th>
              <th class="text-end">Stok</th>
              <th style="width:160px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($products as $p)
              @php
                $cover  = $p->image_url;                // pakai accessor
                $imgCnt = count($p->images_urls ?? []); // jumlah gallery
              @endphp
              <tr>
                <td>
                  <div class="thumb-wrap">
                    <img
                      src="{{ $cover }}"
                      alt="{{ $p->name }}"
                      class="prod-thumb"
                      loading="lazy"
                      data-fallback="{{ asset('images/placeholder.webp') }}"
                      onerror="this.onerror=null; this.src=this.dataset.fallback;"
                    />
                    @if($imgCnt > 1)
                      <span class="badge-count">+{{ $imgCnt - 1 }}</span>
                    @endif
                  </div>
                </td>
                <td>
                  <div class="fw-semibold">{{ $p->name }}</div>
                  <div class="text-muted small">#{{ $loop->iteration }}</div>
                </td>
                <td>{{ $p->category->name ?? '-' }}</td>
                <td class="text-end">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                <td class="text-end">{{ $p->stock }}</td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">
                    @if (Route::has('admin.products.show'))
                      <a href="{{ route('admin.products.show', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="Lihat">
                        <i class="fas fa-eye"></i>
                      </a>
                    @endif
                    @unless($isCatalogue)
                      @if (Route::has('admin.products.edit'))
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                          <i class="fas fa-pen"></i>
                        </a>
                      @endif
                      @if (Route::has('admin.products.destroy'))
                        <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST"
                              onsubmit="return confirm('Hapus produk ini?')">
                          @csrf @method('DELETE')
                          <button class="btn btn-sm btn-outline-danger" title="Hapus">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      @endif
                    @endunless
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">Belum ada produk.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if (method_exists($products,'links'))
        <div class="card-footer d-flex justify-content-center">
          {{ $products->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
