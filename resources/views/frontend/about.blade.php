@extends('layouts.frontend')
@section('title', 'About Us')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;

    // cari gambar about*/bordir* di /public/images atau storage public
    $aboutImg = null;
    $publicDir = public_path('images');
    if (is_dir($publicDir)) {
        foreach (scandir($publicDir) as $f) {
            if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $f) && preg_match('/about|bordir/i', $f)) {
                $aboutImg = asset('images/'.$f);
                break;
            }
        }
    }
    if (!$aboutImg) {
        foreach (Storage::disk('public')->allFiles() as $f) {
            if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $f) && preg_match('/about|bordir/i', $f)) {
                $aboutImg = asset('storage/'.$f);
                break;
            }
        }
    }
    $aboutImg  = $aboutImg ?: asset('images/placeholder.webp');
    $fallback  = asset('images/placeholder.webp');
@endphp

<style>
  :root{
    --grad-start:#de4312;
    --grad-end:#fec163;
    --brand:#ff7b00;
    --brand-rgb:255,123,0;
    --brand-dark:#b55600;

    /* === samakan dengan Home === */
    --font-head:'Sora', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    --font-body:'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    --head-color:#0f172a;   /* ganti ke var(--brand) kalau mau judul oranye */
    --desc-color:#334155;   /* warna deskripsi */
  }

  html, body{ height:100%; }
  body{
    background: linear-gradient(90deg, var(--grad-start) 0%, var(--grad-end) 100%);
    background-attachment: fixed;
    color:#fff;
    font-family: var(--font-body);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  .container-narrow{ max-width:1100px; margin:auto; padding:0 1rem; }
  .section{ padding:56px 0; }

  /* Title section */
  .title{ display:flex; align-items:center; gap:.6rem; margin-bottom:14px; }
  .title h2{
    margin:0; color:#fff; text-shadow:0 1px 0 rgba(0,0,0,.2);
    font-family: var(--font-head);
    font-weight: 800; letter-spacing:.2px;
    font-size: clamp(1.35rem, 1.1rem + 1.4vw, 2.25rem);
    line-height:1.1;
  }
  .subtitle{
    color:rgba(255,255,255,.92);
    margin:.25rem 0 22px;
    font-family: var(--font-body);
    font-weight: 500;
    letter-spacing:.2px;
    font-size: clamp(.98rem, .92rem + .25vw, 1.1rem);
  }

  /* CTA */
  .btn-cta{
    background:linear-gradient(90deg,#ff7b00,#ff9a3d);
    color:#fff!important; font-weight:900;
    padding:.7rem 1.25rem; border-radius:999px; border:none;
    box-shadow:0 14px 30px rgba(0,0,0,.25);
    transition:.2s; letter-spacing:.2px; font-family:var(--font-head);
  }
  .btn-cta:hover{ transform:translateY(-2px); opacity:.96; color:#fff!important; }

  /* Card */
  .about-card{
    background:rgba(255,255,255,.95);
    color:#111;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 18px 40px rgba(0,0,0,.25);
    padding:1.25rem;
  }

  /* Gambar hero */
  .about-hero{
    padding:12px;                          /* tebal bingkai */
    border-radius:22px;                     /* sudut bingkai */
    background: rgba(255,255,255,.97);      /* warna bingkai */
    border:1px solid rgba(0,0,0,.06);       /* garis tipis di bingkai */
    box-shadow:0 10px 28px rgba(0,0,0,.18); /* bayangan bingkai */
  }

  /* gambar di dalam bingkai */
  .about-hero img{
    width:100%;
    height:auto;             /* biar proporsi asli, tidak kepotong */
    border-radius:16px;      /* sudut foto */
    display:block;
    object-fit:contain;      /* tampil utuh; pakai 'cover' kalau mau isi penuh */
    background:#f6f6f6;      /* latar lembut kalau ada area kosong */
  }

  .about-hero:hover img{ transform:scale(1.03); filter:saturate(1.05); }

  /* Judul & isi di kanan */
  .about-title{
    margin:0 0 .5rem;
    color:var(--head-color);
    font-family: var(--font-head);
    font-weight:800; letter-spacing:.2px;
    font-size: clamp(1.25rem, 1.1rem + .8vw, 1.8rem);
    line-height:1.15;
  }

  .about-text p{
    margin:0 0 .75rem;
    color:var(--desc-color);
    font-family: var(--font-body);
    font-weight:500; letter-spacing:.12px;
    font-size: clamp(1rem, .96rem + .25vw, 1.08rem);
    line-height:1.78;

    text-align: justify;       /* ⭐ Rata kiri kanan */
    text-justify: inter-word;  /* ⭐ Supaya jarak antar kata rapi */
  }
</style>

<div class="page">
  <section class="section">
    <div class="container-narrow">
      <div class="title"><i class="fa-solid fa-circle-info"></i><h2>About Us</h2></div>
      <p class="subtitle">Luxurious Embroidery – Exclusive Bordir</p>

      <div class="about-card">
        <div class="row align-items-center">
          <div class="col-md-6 mb-4 mb-md-0 about-hero">
            <img
              src="{{ $aboutImg }}"
              alt="About Us"
              loading="lazy"
              data-fallback="{{ $fallback }}"
              onerror="this.onerror=null; this.src=this.dataset.fallback;">
          </div>

          <div class="col-md-6">
            <h3 class="about-title">Bina Bordir - Our History</h3>
            <div class="about-text">
              <p>Bina Bordir was established in 2013. With more than 12 years of experience, Bina Bordir offers strong expertise in handmade embroidery design, as well as reliable production capabilities for both retail and bulk orders. The company is an Indonesian manufacturer specializing in handmade embroidery.</p>
              <p>As a fashion producer, Bina Bordir highlights Indonesia’s local wisdom through traditional handmade embroidery and kerancang kejek craftsmanship. Each design is uniquely created by local women artisans, promoting sustainable fashion and women’s empowerment.</p>
              <p>Bina Bordir produces beautiful handmade embroidery with high-quality fabrics and refined designs. Our creations include embroidery for Muslim prayer items, Muslim clothing, traditional Indonesian garments such as Kebaya, and luxury bedding goods (duvet covers, blankets, fitted sheets, cushions).</p>
              <p>All products are made using high-grade materials such as silk, cotton, semi-cotton, and traditional Indonesian textiles including Batik and Tenun. Bina Bordir is committed to originality in embroidery design and trend innovation, while also offering export services to markets in Malaysia, Singapore, the Netherlands, America, Australia, and Germany.</p>
            </div>
            <a href="{{ url('/') }}" class="btn-cta mt-2"><i class="fa-solid fa-arrow-left"></i> Back</a>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
@endsection
