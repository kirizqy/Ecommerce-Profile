@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')

@push('styles')
<style>
  .bb-chip-title{
    background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block
  }
  .form-label{ font-weight:600 }
  .card { overflow: hidden }
</style>
@endpush

{{-- Header atas (chip + tombol kembali) --}}
@section('header')
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div><span class="bb-chip-title">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}</span></div>
    <div class="ms-auto">
      @if (Route::has('admin.categories.index'))
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
          <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
      @endif
    </div>
  </div>
@endsection

@section('content')
<div class="container-fluid">
  {{-- Alert sukses kalau redirect balik ke form --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa-regular fa-circle-check me-1"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- Tampilkan error validasi --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1">Periksa kembali input kamu:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}</h5>
    </div>

    <div class="card-body">
      <form id="js-cat-form"
            action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
            method="POST" novalidate>
        @csrf
        @if(isset($category)) @method('PUT') @endif

        {{-- Nama Kategori --}}
        <div class="mb-3">
          <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
          <input type="text"
                 id="name"
                 name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 placeholder="Tulis nama kategori…"
                 value="{{ old('name', $category->name ?? '') }}"
                 maxlength="100"
                 autocomplete="off"
                 required
                 autofocus>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="form-text">Maksimal 100 karakter. Contoh: “Pakaian”, “Bordir Komputer”.</div>
          @enderror
        </div>

        {{-- Tombol aksi --}}
        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            Batal
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fa-regular fa-floppy-disk me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Cegah double submit + disabled tombol saat submit --}}
<script>
(function(){
  const form = document.getElementById('js-cat-form');
  if(!form) return;

  form.addEventListener('submit', function(e){
    const btns = form.querySelectorAll('button[type="submit"]');
    btns.forEach(b => { b.disabled = true; b.innerHTML = 'Menyimpan…'; });
  }, {once:true});
})();
</script>
@endsection
