@extends('layouts.frontend')
@section('title','Contact')

@section('content')
@php
  use Illuminate\Support\Facades\Route;

  // action form → pakai nama route yang ada di web.php
  $contactAction = Route::has('contact.store') ? route('contact.store') : url('/contact');

  // Alamat tetap
  $address = 'Jl. Sanggar Kencana XVI No.5, Jatisari, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286';

  // Google Maps embed & link
  $mapQuery = rawurlencode($address);
  $mapSrc   = "https://www.google.com/maps?q={$mapQuery}&output=embed";
  $mapLink  = "https://www.google.com/maps?q={$mapQuery}";
@endphp

<style>
  :root{
    --grad-start:#de4312;
    --grad-end:#fec163;
    --brand:#ff7b00;
    --brand-rgb:255,123,0;
    --brand-dark:#b55600;

    /* Tipografi seragam */
    --font-head:'Sora', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    --font-body:'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  }
  body{
    background: linear-gradient(90deg, var(--grad-start) 0%, var(--grad-end) 100%);
    background-attachment: fixed;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  .page{ padding-top:80px; color:#fff; }
  .container-narrow{ max-width:960px; margin-inline:auto; padding:0 1rem; }
  .section{ padding:40px 0; }

  /* Header */
  .title{ display:flex; align-items:center; gap:.6rem; margin:0; }
  .title h2{
    margin:0; color:#fff; text-shadow:0 1px 0 rgba(0,0,0,.2);
    font-family:var(--font-head); font-weight:800; letter-spacing:.2px;
    font-size: clamp(1.35rem, 1.1rem + 1.4vw, 2.25rem);
    line-height:1.1;
  }
  .subtitle{
    color:rgba(255,255,255,.92); margin:10px 0 22px;
    font-family:var(--font-body); font-weight:500; letter-spacing:.2px;
    font-size: clamp(.98rem, .92rem + .25vw, 1.1rem);
  }

  /* Cards */
  .card-lite{
    background:rgba(255,255,255,.96);
    color:#111;
    border-radius:18px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 18px 36px rgba(0,0,0,.22);
  }
  .form-card{ padding:1.5rem; }
  .map-card{ padding:1rem; }

  /* Inputs */
  .form-label{ font-weight:700; font-family:var(--font-head); letter-spacing:.2px; }
  .form-control{
    border-radius:12px;
    border:1px solid rgba(0,0,0,.1);
    padding:.7rem .9rem;
    font-family:var(--font-body);
    font-size:1rem;
  }
  .form-control::placeholder{ color:#9aa3af; }
  .form-control:focus{
    border-color:var(--brand);
    box-shadow:0 0 0 .2rem rgba(var(--brand-rgb),.25);
  }

  /* Buttons */
  .btn, .btn:visited{ text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; }
  .btn-cta{
    background:linear-gradient(90deg,#ff7b00,#ff9a3d);
    color:#fff!important; font-weight:900;
    padding:.66rem 1.2rem; border-radius:999px;
    box-shadow:0 14px 30px rgba(0,0,0,.25);
    border:none; transition:.2s; white-space:nowrap;
    font-family:var(--font-head); letter-spacing:.2px;
  }
  .btn-cta:hover{ transform:translateY(-2px); opacity:.96; }

  /* Map embed */
  .map-embed{
    width:100%; aspect-ratio: 16/9; border:0; border-radius:12px;
    box-shadow:0 8px 22px rgba(0,0,0,.08);
  }
  .addr{
    display:flex; align-items:flex-start; gap:.6rem; color:#333; margin:.5rem 0 0;
    font-family:var(--font-body);
  }

  /* Alerts */
  .alert{ border-radius:12px; font-family:var(--font-body); }
</style>

<div class="page">
  <section class="section">
    <div class="container-narrow">

      {{-- Header + Back --}}
      <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div class="title">
          <i class="fa-solid fa-envelope"></i><h2>Contact Us</h2>
        </div>
        <a href="{{ url('/') }}" class="btn-cta mt-2 mt-sm-0">
          <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
      </div>
      <p class="subtitle">Send your questions, suggestions, or requests through the form below.</p>

      {{-- Flash success --}}
      @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
      @endif

      {{-- Ringkasan error --}}
      @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
          <div class="fw-semibold mb-1">Please check the form:</div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Contact Form --}}
      <div class="card-lite form-card mb-4">
        <form action="{{ $contactAction }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input
              type="text"
              name="name"
              id="name"
              class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name') }}"
              required
              autocomplete="name"
              placeholder="Your name">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
              type="email"
              name="email"
              id="email"
              class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email') }}"
              required
              autocomplete="email"
              placeholder="email@domain.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number <span class="text-muted">(optional)</span></label>
            <input
              type="text"
              name="phone"
              id="phone"
              class="form-control"
              value="{{ old('phone') }}"
              autocomplete="tel"
              placeholder="Optional">
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea
              name="message"
              id="message"
              rows="5"
              class="form-control @error('message') is-invalid @enderror"
              required
              placeholder="Write your message...">{{ old('message') }}</textarea>
            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-cta">
              <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
          </div>
        </form>
      </div>

      {{-- Address + Map --}}
      <div class="card-lite map-card">
        <h5 class="mb-2" style="font-family:var(--font-head);font-weight:800;">Our Address</h5>
        <div class="addr">
          <i class="fa-solid fa-location-dot mt-1"></i>
          <div>
            <div class="fw-semibold">{{ $address }}</div>
            <a class="btn btn-cta mt-2" href="{{ $mapLink }}" target="_blank" rel="noopener">
              <i class="fa-solid fa-map"></i> Open in Google Maps
            </a>
          </div>
        </div>
        <div class="mt-3">
          <iframe class="map-embed"
                  src="{{ $mapSrc }}"
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  allowfullscreen></iframe>
        </div>
      </div>

    </div>
  </section>
</div>
@endsection
