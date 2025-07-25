@extends('layouts.admin')

@section('title', 'Daftar Gallery')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Gallery</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="{{ route('admin.galleries.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Gallery
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="row">
            @forelse ($galleries as $gallery)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ asset($gallery->image) }}" class="card-img-top" alt="{{ $gallery->title }}">
                    <div class="card-body">
                        <p class="card-text">{{ $gallery->title }}</p>
                        <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus gambar ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center">Belum ada gambar.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection