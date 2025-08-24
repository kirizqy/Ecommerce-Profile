@extends('layouts.admin')
@section('title','Tambah Produk')

@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Tambah Produk</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
      <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <div class="fw-semibold mb-1">Periksa kembali formulir:</div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Penting: enctype wajib untuk upload --}}
      <form action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="product-form"
            autocomplete="off">
        @csrf

        @include('admin.products.partials.form', [
          'product'    => $product ?? null,
          'categories' => $categories ?? collect(),
          'min'        => 0,   // tanpa minimal
          'max'        => 8,   // maksimal 8
        ])

        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Simpan
          </button>
          <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection