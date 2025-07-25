@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-dark text-white">
            <h4 class="mb-0">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}</h4>
        </div>
    <div class="card-body">
      <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($category))
          @method('PUT')
        @endif

        <div class="form-group">
          <label for="name">Nama Kategori</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
</div>
@endsection