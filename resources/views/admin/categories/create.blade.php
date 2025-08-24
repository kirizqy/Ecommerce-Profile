@extends('layouts.admin')
@section('title','Tambah Kategori')

@section('header')
  <div class="d-flex flex-wrap gap-2 align-items-center">
    <h1 class="m-0 me-auto">Tambah Kategori</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
      <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('admin.categories.store') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-6">
          <label class="form-label">Nama Kategori</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name') }}" placeholder="Misal: Batik" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
          <button class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endsection
