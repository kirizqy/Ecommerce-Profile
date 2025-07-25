@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif

                {{-- Nama Produk --}}
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name ?? '') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @isset($categories)
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        @endisset
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="form-group">
                    <label>Gambar</label>
                    @if(!empty($product->image))
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->image) }}" width="120" alt="Gambar Produk">
                    </div>
                    @endif
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                    @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Link Shopee --}}
                <div class="form-group">
                    <label>Link Shopee</label>
                    <input type="url" name="shopee_link" class="form-control @error('shopee_link') is-invalid @enderror"
                        value="{{ old('shopee_link', $product->shopee_link ?? '') }}">
                    @error('shopee_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Link Tokopedia --}}
                <div class="form-group">
                    <label>Link Tokopedia</label>
                    <input type="url" name="tokopedia_link" class="form-control @error('tokopedia_link') is-invalid @enderror"
                        value="{{ old('tokopedia_link', $product->tokopedia_link ?? '') }}">
                    @error('tokopedia_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Link WhatsApp --}}
                <div class="form-group">
                    <label>Link WhatsApp</label>
                    <input type="url" name="whatsapp_link" class="form-control @error('whatsapp_link') is-invalid @enderror"
                        value="{{ old('whatsapp_link', $product->whatsapp_link ?? '') }}">
                    @error('whatsapp_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">{{ $buttonText ?? 'Simpan' }}</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection