@extends('layouts.frontend')

@section('content')
<!-- SLIDESHOW -->
<section id="hero-carousel" class="mb-5">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicator -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
        </div>
        <!-- Slides -->
        <div class="carousel-inner">
            @foreach ($sliders as $index => $slider)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $slider->image) }}" class="d-block w-100" alt="{{ $slider->title ?? 'Slide' }}">
            </div>
            @endforeach
        </div>
        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- BRAND GALLERY -->
<section class="brand-gallery container mb-5">
    <h2 class="mb-4 text-center">Our Gallery</h2>
    <div class="row">
        <!-- Example Gallery -->
        @foreach ($galleries as $gallery)
        <div class="col-md-4 mb-3">
            <img src="{{ asset('storage/' . $gallery->image) }}" class="img-fluid" alt="Gallery">
        </div>
        @endforeach
    </div>
    <div class="text-center">
        <a href="{{ url('/gallery') }}" class="btn btn-outline-dark">Read More</a>
    </div>
</section>

<!-- PRODUCT PREVIEW -->
<section class="product-preview container mb-5">
    <h2 class="mb-4 text-center">Our Products</h2>
    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                    <a href="{{ url('/product/' . $product->slug) }}" class="btn btn-outline-dark">Read More</a>
                </div>
            </div>
        </div>
        @endforeach
        <div class="text-center mt-3">
            <a href="{{ url('/catalogue') }}" class="btn btn-outline-dark">See More Product</a>
        </div>
    </div>
</section>

<!-- ABOUT US PREVIEW -->
<section class="about-preview container mb-5">
    <h2 class="mb-4 text-center">About Us</h2>
    <p class="text-center">
        Bina Bordir didirikan sejak tahun 2005. Dengan pengalaman lebih dari 19 tahun, Bina Bordir menawarkan keahlian dalam desain bordir handmade, kapasitas produksi untuk kebutuhan retail maupun makloon, serta reputasi sebagai produsen bordir handmade berkualitas di Indonesia.
    </p>
    <div class="text-center">
        <a href="{{ url('/about') }}" class="btn btn-outline-dark">Read More</a>
    </div>
</section>

@endsection