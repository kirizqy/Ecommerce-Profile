@extends('layouts.admin')
@section('title', 'Tambah Gallery')

@section('content')
<div class="container-fluid py-3">

  <div class="card shadow-sm">
    <div class="card-header">
      <h5 class="mb-0">Tambah Gallery</h5>
    </div>

    <div class="card-body">
      <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Judul --}}
        <div class="mb-3">
          <label class="form-label">Judul (opsional)</label>
          <input type="text" name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
          <label class="form-label">Deskripsi (opsional)</label>
          <textarea name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Media (image/video) --}}
        <div class="mb-3">
          <label class="form-label">Media (Gambar atau Video)</label>
          <input type="file" name="media" id="mediaInput"
                 class="form-control @error('media') is-invalid @enderror"
                 accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v">
          <div class="form-text">
            Dukung: JPG/PNG/WebP/GIF atau MP4/WebM/OGG/MOV. Maks 1GB (sesuai konfigurasi server). Boleh dikosongkan.
          </div>
          @error('media')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          {{-- Preview --}}
          <div id="mediaPreview" class="mt-2 d-none">
            <img id="imgPreview" class="img-fluid rounded d-none" style="max-height:260px" alt="preview">
            <video id="vidPreview" class="d-none w-100" style="max-height:260px" controls></video>
          </div>
        </div>

        {{-- Poster untuk video (opsional) --}}
        <div class="mb-3">
          <label class="form-label">Poster (opsional, untuk video)</label>
          <input type="file" name="poster" id="posterInput"
                 class="form-control @error('poster') is-invalid @enderror"
                 accept="image/*">
          <div class="form-text">Dipakai sebagai thumbnail video. Maks 4MB. Opsional.</div>
          @error('poster')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          {{-- Preview Poster --}}
          <div id="posterPreviewWrap" class="mt-2 d-none">
            <img id="posterPreview" class="img-fluid rounded" style="max-height:160px" alt="poster preview">
          </div>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-save me-1"></i> Simpan
          </button>
          <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>

</div>

{{-- Preview Script --}}
<script>
(function() {
  const mediaInput   = document.getElementById('mediaInput');
  const mediaPreview = document.getElementById('mediaPreview');
  const imgPreview   = document.getElementById('imgPreview');
  const vidPreview   = document.getElementById('vidPreview');

  const posterInput      = document.getElementById('posterInput');
  const posterPreviewWrap= document.getElementById('posterPreviewWrap');
  const posterPreview    = document.getElementById('posterPreview');

  function resetMediaPreview(){
    imgPreview.src = ''; imgPreview.classList.add('d-none');
    vidPreview.src = ''; vidPreview.classList.add('d-none');
    mediaPreview.classList.add('d-none');
  }

  function resetPosterPreview(){
    posterPreview.src = ''; posterPreviewWrap.classList.add('d-none');
  }

  mediaInput?.addEventListener('change', function(e){
    resetMediaPreview();
    const f = e.target.files && e.target.files[0];
    if(!f) return;

    const url = URL.createObjectURL(f);
    if (f.type.startsWith('video/')) {
      vidPreview.src = url;
      vidPreview.classList.remove('d-none');
      mediaPreview.classList.remove('d-none');
    } else if (f.type.startsWith('image/')) {
      imgPreview.src = url;
      imgPreview.classList.remove('d-none');
      mediaPreview.classList.remove('d-none');
    }
  });

  posterInput?.addEventListener('change', function(e){
    resetPosterPreview();
    const f = e.target.files && e.target.files[0];
    if(!f) return;
    const url = URL.createObjectURL(f);
    posterPreview.src = url;
    posterPreviewWrap.classList.remove('d-none');
  });
})();
</script>
@endsection
