@extends('layouts.frontend')

@section('title', 'About Us')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Tentang Bina Bordir</h2>
        <p class="text-muted">Luxurious Embroidery - Bordir Eksklusif</p>
    </div>

    <div class="row mb-5 align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="{{ asset('images/about-image.jpg') }}" class="img-fluid rounded shadow" alt="Tentang Kami">
        </div>
        <div class="col-md-6">
            <h4 class="fw-semibold">Sejarah Kami</h4>
            <p>
                Bina Bordir didirikan sejak tahun 2005. Dengan pengalaman lebih dari 19 tahun, Bina Bordir menawarkan keahlian dalam desain bordir handmade, kapasitas produksi untuk kebutuhan retail maupun makloon, serta reputasi sebagai produsen bordir handmade berkualitas di Indonesia.
            </p>
            <p>
                Kami memproduksi bordir handmade untuk perlengkapan ibadah Muslim, pakaian Muslim, Kebaya, serta produk tekstil mewah seperti sprei, selimut, dan bantal. Semua produk kami dibuat dari bahan berkualitas tinggi seperti sutra, katun, semi katun, dan kain etnik Indonesia seperti Batik dan Tenun.
            </p>
            <p>
                Bina Bordir juga menawarkan desain bordir orisinal dan mengikuti arah tren mode terkini. Kami melayani pembelian ekspor ke berbagai negara seperti Malaysia, Singapura, Belanda, Amerika, Australia, dan Jerman.
            </p>
            <p>
                Kami berkomitmen memberikan kualitas terbaik dalam setiap produk handmade kami, mulai dari bahan, bordiran, hingga desain orisinal untuk kepuasan pelanggan.
            </p>
        </div>
    </div>
</div>
@endsection