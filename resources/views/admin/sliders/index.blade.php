@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manajemen Slider</h4>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">+ Tambah Slider</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>No</th>
        <th>Gambar</th>
        <th>Judul</th>
        <th>Status</th>
        <th>Urutan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($sliders as $slider)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><img src="{{ asset('storage/sliders/' . $slider->image) }}" width="120"></td>
          <td>{{ $slider->title }}</td>
          <td>
            <span class="badge bg-{{ $slider->status ? 'success' : 'secondary' }}">
              {{ $slider->status ? 'Aktif' : 'Nonaktif' }}
            </span>
          </td>
          <td>{{ $slider->order }}</td>
          <td>
            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus slider ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center">Belum ada data slider</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection