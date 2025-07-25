@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Upload Gambar Galeri</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label>Judul Gambar (opsional)</label>
          <input type="text" name="title" class="form-control">
        </div>

        <div class="form-group">
          <label>Gambar</label>
          <input type="file" name="image" class="form-control" required>
        </div>

        <button class="btn btn-success" type="submit">Simpan</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
</div>
@endsection