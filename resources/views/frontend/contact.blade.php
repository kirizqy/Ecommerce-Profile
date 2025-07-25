@extends('layouts.frontend')

@section('title', 'Hubungi Kami')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Hubungi Kami</h1>

    <div class="row">
        <!-- KIRI: Info Kontak -->
        <div class="col-md-6 mb-4">
            <h4>Informasi Kontak</h4>
            <ul class="list-unstyled">
                <li class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-telephone"></i>
                    <span>6281234567890 (WhatsApp)</span>
                </li>
                <li class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-envelope"></i>
                    <span>info@binabordir.com</span>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-geo-alt"></i>
                    <span>
                        Sanggar Kencana XVI No. 5 Sanggar Hurip Estate, Bandung, 40286, West Java, Indonesia.
                    </span>
                </li>
            </ul>
        </div>

        <!-- KANAN: Form Kirim Pesan -->
        <div class="col-md-6 mb-4">
            <h4>Kirim Pesan</h4>
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Pesan</label>
                    <textarea name="message" class="form-control" id="message" rows="5" placeholder="Tulis pesan Anda" required></textarea>
                </div>
                <button type="submit" class="btn btn-dark">Kirim</button>
            </form>
        </div>
    </div>

    <!-- GOOGLE MAP DI BAWAH -->
    <div class="row">
        <div class="col-12">
            <h4 class="mt-5 mb-3">Lokasi Kami</h4>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.6355250949728!2d107.66101297799732!3d-6.934091749972231!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e80301fa6545%3A0x396208226a58998d!2sJl.%20Sanggar%20Kencana%20XVI%20No.5%2C%20Jatisari%2C%20Kec.%20Buahbatu%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040286!5e0!3m2!1sid!2sid!4v1753152510206!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection