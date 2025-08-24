@extends('layouts.admin')
@section('title','Detail Produk')

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-1">{{ $product->name }}</h5>
      <div class="text-muted small mb-3">#{{ $product->id }}</div>

      <p>Kategori: {{ $product->category->name ?? '-' }}</p>
      <p>Harga: Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
      <p>Stok: {{ $product->stock ?? 0 }}</p>

      <hr>
      <div class="fw-semibold">Deskripsi</div>
      <div>{!! $product->description ? nl2br(e($product->description)) : '<span class="text-muted">Belum ada deskripsi.</span>' !!}</div>

      <div class="mt-3">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </div>
  </div>
@endsection
