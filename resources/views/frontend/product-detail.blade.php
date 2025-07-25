@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $product->name }}">
        </div>
        <div class="col-md-6">
            <h2 class="mb-3">{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->tagline }}</p>
            <p>{{ $product->description }}</p>

            <h5 class="mt-4">Beli Produk Ini :</h5>
            <div class="d-flex gap-2 mt-3">
                @if ($product->shopee_link)
                <a href="{{ $product->shopee_link }}" target="_blank" class="btn btn-danger">
                    <i class="fab fa-shopee"></i> Shopee
                </a>
                @endif

                @if ($product->tokopedia_link)
                <a href="{{ $product->tokopedia_link }}" target="_blank" class="btn btn-success">
                    <i class="fas fa-store"></i> Tokopedia
                </a>
                @endif

                @if ($product->whatsapp_link)
                <a href="{{ $product->whatsapp_link }}?text={{ urlencode('Halo, apakah produk *' . $product->name . '* masih tersedia?') }}" target="_blank" class="btn btn-success">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection