@extends('layouts.admin')
@section('title','Edit Produk')

@section('header')
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Edit Produk</h1>
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
            @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data"
            id="product-form"
            autocomplete="off">
        @csrf
        @method('PUT')

        @include('admin.products.partials.form', [
          'product'    => $product ?? null,
          'categories' => $categories ?? collect(),
          'min'        => 0,   // tanpa minimal
          'max'        => 8,   // maksimal 8
        ])

        <div class="mt-3 d-flex gap-2">
          <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
          <button type="submit" class="btn btn-primary ms-auto">
            <i class="fa-solid fa-save me-1"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

{{-- Pastikan di layouts.admin ada @stack('scripts') sebelum </body> --}}
