@php
  /** @var \App\Models\Slider|null $slider */
  $isEdit   = isset($slider);
  $imgUrl   = $isEdit && $slider->image
              ? asset('storage/'.$slider->image)
              : asset('images/placeholder.webp');
@endphp

<div class="card shadow-sm border-0">
  <div class="card-body">
    {{-- Judul --}}
    <div class="mb-3">
      <label class="form-label">Judul</label>
      <input type="text" name="title" value="{{ old('title', $slider->title ?? '') }}"
             class="form-control @error('title') is-invalid @enderror" placeholder="Judul slider (opsional)">
      @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Deskripsi --}}
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" rows="3"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Deskripsi (opsional)">{{ old('description', $slider->description ?? '') }}</textarea>
      @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3">
      {{-- Urutan --}}
      <div class="col-sm-6 col-md-4">
        <label class="form-label">Urutan</label>
        <input type="number" name="order" value="{{ old('order', $slider->order ?? 0) }}"
               class="form-control @error('order') is-invalid @enderror">
        @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Status --}}
      <div class="col-sm-6 col-md-4 d-flex align-items-end">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="status"
                 name="status" value="1"
                 @checked(old('status', $slider->status ?? true))>
          <label class="form-check-label" for="status">Aktif</label>
        </div>
      </div>
    </div>

    {{-- Gambar --}}
    <div class="mt-3">
      <label class="form-label">Gambar <small class="text-muted">(jpg, jpeg, png, webp • maks 2MB)</small></label>
      <input type="file" name="image" id="image"
             class="form-control @error('image') is-invalid @enderror"
             accept=".jpg,.jpeg,.png,.webp">
      @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror>

      <div class="mt-3">
        <img id="preview" src="{{ $imgUrl }}" alt="preview" class="rounded border" style="max-width: 380px; height:auto;">
      </div>
    </div>
  </div>

  <div class="card-footer bg-white d-flex gap-2">
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-dark ms-auto">{{ $button ?? 'Simpan' }}</button>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('change', (e) => {
    if (e.target.id === 'image') {
      const file = e.target.files?.[0];
      if (!file) return;
      const img = document.getElementById('preview');
      img.src = URL.createObjectURL(file);
    }
  });
</script>
@endpush
