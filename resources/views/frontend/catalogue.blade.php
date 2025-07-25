@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Catalogue Produk</h2>

    {{-- Filter Kategori --}}
    <div class="mb-4 text-center">
        <form action="{{ route('catalogue') }}" method="GET" class="d-inline-block">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Produk --}}
    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('images/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->tagline }}</p>
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-dark">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p>Tidak ada produk dalam kategori ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection