@extends('layouts.admin')

@php
  // Fallback aman kalau controller belum supply
  $totalProducts   = $totalProducts   ?? 0;
  $totalCategories = $totalCategories ?? 0;
  $latestProducts  = $latestProducts  ?? [];
@endphp

@section('title','Dashboard')

@push('styles')
<style>
  /* ====== Dashboard Polish ====== */
  .bb-card{border:1px solid rgba(0,0,0,.06);border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,.06);overflow:hidden;background:#fff}
  .bb-card-header{padding:.85rem 1rem;background:#f7f8fb;border-bottom:1px solid rgba(0,0,0,.06);font-weight:600}
  .bb-stat{border-radius:14px;padding:16px;display:flex;gap:14px;align-items:center;border:1px solid rgba(0,0,0,.06);box-shadow:0 6px 18px rgba(0,0,0,.06);transition:transform .15s,box-shadow .15s;background:#fff}
  .bb-stat:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(0,0,0,.08)}
  .bb-stat .bb-icon{width:48px;height:48px;display:grid;place-items:center;border-radius:12px;color:#fff;flex:0 0 auto}
  .bg-grad-blue{background:linear-gradient(135deg,#00c2ff,#00a0ff)}
  .bg-grad-green{background:linear-gradient(135deg,#00c48c,#00a87a)}
  .bg-grad-amber{background:linear-gradient(135deg,#ffb02e,#ff8c00)}
  .bb-stat h3{margin:0;font-size:1.6rem;line-height:1}
  .bb-stat small{color:#6c757d}
  .bb-actions .btn{border-radius:10px}
  .table>:not(caption)>*>*{padding:.6rem .8rem} /* compact */
  .table thead th{font-weight:600}
  .avatar-rect{width:60px;height:40px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.06);background:#f3f4f6}
  .bb-empty{text-align:center;padding:3rem 1rem;color:#8892a0}

  /* Chip judul kecil di kiri (gantikan H1 besar) */
  .bb-chip-title{
    background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block
  }
</style>
@endpush

{{-- Header: chip di kiri, tombol di kanan --}}
@section('header')
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div><span class="bb-chip-title">Dashboard</span></div>
    <div class="bb-actions d-flex gap-2 ms-auto">
      @if (Route::has('admin.products.create'))
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
          <i class="fa-solid fa-plus me-1"></i> Tambah Produk
        </a>
      @endif
      @if (Route::has('admin.products.index'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
          <i class="fa-solid fa-box me-1"></i> Kelola Produk
        </a>
      @endif
    </div>
  </div>
@endsection

@section('content')
<div class="container-fluid">

  {{-- Stat Cards --}}
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="bb-stat">
        <div class="bb-icon bg-grad-blue"><i class="fa-solid fa-box"></i></div>
        <div class="flex-grow-1">
          <small>Produk</small>
          <div class="d-flex align-items-end justify-content-between mt-1">
            <h3>{{ $totalProducts }}</h3>
            @if (Route::has('admin.products.index'))
              <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Lihat detail
                <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="bb-stat">
        <div class="bb-icon bg-grad-green"><i class="fa-solid fa-tags"></i></div>
        <div class="flex-grow-1">
          <small>Kategori</small>
          <div class="d-flex align-items-end justify-content-between mt-1">
            <h3>{{ $totalCategories }}</h3>
            @if (Route::has('admin.categories.index'))
              <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Lihat detail
                <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="bb-stat">
        <div class="bb-icon bg-grad-amber"><i class="fa-solid fa-list"></i></div>
        <div class="flex-grow-1">
          <small>Katalog (lihat)</small>
          <div class="d-flex align-items-end justify-content-between mt-1">
            <h3>{{ $totalProducts }}</h3>
            @if (Route::has('admin.catalogue.index'))
              <a href="{{ route('admin.catalogue.index') }}" class="text-decoration-none">Buka
                <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
            @elseif (Route::has('admin.products.index'))
              <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Buka
                <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="bb-stat">
        <div class="bb-icon bg-dark"><i class="fa-solid fa-chart-line"></i></div>
        <div class="flex-grow-1">
          <small>Ringkasan</small>
          <div class="d-flex align-items-end justify-content-between mt-1">
            <h3>—</h3>
            @if (Route::has('admin.dashboard'))
              <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Refresh
                <i class="fa-solid fa-rotate-right ms-1"></i></a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Produk Terbaru --}}
  <div class="bb-card">
    <div class="bb-card-header">Produk Terbaru</div>
    <div class="card-body p-0">
      @if(!empty($latestProducts) && count($latestProducts))
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:80px">Gambar</th>
                <th>Nama</th>
                <th style="width:200px">Kategori</th>
                <th style="width:160px" class="text-end">Dibuat</th>
                <th style="width:160px" class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
            @foreach($latestProducts as $p)
              @php
                $thumb = $p->image_url
                  ?? ($p->image ? asset('storage/'.preg_replace('#^public/#','',$p->image)) : 'https://placehold.co/120x80?text=No+Img');
              @endphp
              <tr>
                <td>
                  <img class="avatar-rect" src="{{ $thumb }}" alt="{{ $p->name }}"
                       onerror="this.onerror=null;this.src='https://placehold.co/120x80?text=No+Img';">
                </td>
                <td class="fw-semibold">
                  {{ $p->name }}
                  <div class="text-muted small">#{{ $p->id }}</div>
                </td>
                <td>{{ $p->category->name ?? '—' }}</td>
                <td class="text-end">{{ $p->created_at?->format('d M Y H:i') }}</td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    @if (Route::has('admin.products.show'))
                      <a class="btn btn-outline-secondary" href="{{ route('admin.products.show',$p->id) }}" title="Lihat">
                        <i class="fa-solid fa-eye"></i>
                      </a>
                    @endif
                    @if (Route::has('admin.products.edit'))
                      <a class="btn btn-outline-primary" href="{{ route('admin.products.edit',$p->id) }}" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                      </a>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="bb-empty">
          <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
          Belum ada produk terbaru.
        </div>
      @endif
    </div>
    <div class="card-footer d-flex justify-content-end">
      @if (Route::has('admin.products.index'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Lihat semua</a>
      @endif
    </div>
  </div>
</div>
@endsection
