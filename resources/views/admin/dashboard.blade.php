@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
  <div class="row">
    <!-- Kartu Jumlah Produk -->
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $totalProducts }}</h3>
          <p>Produk</p>
        </div>
        <div class="icon">
          <i class="fas fa-box"></i>
        </div>
        <a href="{{ route('admin.products.index') }}" class="small-box-footer">
          Lihat Detail <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>

    <!-- Kartu Jumlah Kategori -->
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $totalCategories }}</h3>
          <p>Kategori</p>
        </div>
        <div class="icon">
          <i class="fas fa-tags"></i>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="small-box-footer">
          Lihat Detail <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>

    <!-- Tambahan jika ada fitur lainnya nanti -->
  </div>
</div>
@endsection