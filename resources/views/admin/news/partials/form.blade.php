@php
  $val = fn($field, $default = null) =>
    old($field, isset($news) ? ($news[$field] ?? $default) : $default);

  $placeholder = asset('images/placeholder.webp');
  $hasImage    = isset($news) && !empty($news->image);
  $imageUrl    = $hasImage ? asset('storage/'.$news->image) : $placeholder;
@endphp

<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ $val('title') }}" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Slug (opsional)</label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
           value="{{ $val('slug') }}" placeholder="otomatis kalau kosong">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-12">
    <label class="form-label">Konten</label>
    {{-- NOTE: name="body" supaya kompatibel dgn controller (akan dipetakan ke content) --}}
    <textarea name="body" rows="8"
              class="form-control @error('body') is-invalid @enderror"
              placeholder="Tulis isi berita...">{{ old('body', $news->body ?? $news->content ?? '') }}</textarea>
    @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror">
      @php
        $status = strtolower((string) $val('status','draft'));
      @endphp
      <option value="draft"     {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
      <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Tanggal Publish</label>
    <input type="datetime-local" name="published_at"
           value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}"
           class="form-control @error('published_at') is-invalid @enderror">
    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Kosongkan jika masih draft.</div>
  </div>

  <div class="col-md-4">
    <label class="form-label">Gambar (opsional)</label>
    <input type="file" name="image" accept="image/*"
           class="form-control @error('image') is-invalid @enderror"
           onchange="previewNewsImage(this)">
    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">Preview Gambar</label>
    <div>
      <img id="news-image-preview" src="{{ $imageUrl }}"
           style="width:160px;height:160px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb">
    </div>
    @if($hasImage)
      <div class="form-text mt-1">Tersimpan: <code>/storage/{{ $news->image }}</code></div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  function previewNewsImage(input){
    if (!input.files || !input.files[0]) return;
    const img = document.getElementById('news-image-preview');
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; };
    reader.readAsDataURL(input.files[0]);
  }
</script>
@endpush
