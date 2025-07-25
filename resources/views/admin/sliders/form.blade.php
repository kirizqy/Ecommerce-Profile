<div class="mb-3">
  <label>Judul</label>
  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
    value="{{ old('title', $slider->title ?? '') }}">
  @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label>Deskripsi</label>
  <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $slider->description ?? '') }}</textarea>
  @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label>Urutan Slide</label>
  <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
    value="{{ old('order', $slider->order ?? 0) }}">
  @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label>Status</label>
  <select name="status" class="form-control @error('status') is-invalid @enderror">
    <option value="1" {{ old('status', $slider->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
    <option value="0" {{ old('status', $slider->status ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
  </select>
  @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label>Gambar</label><br>
  @if(!empty($slider->image))
    <img src="{{ asset('storage/sliders/' . $slider->image) }}" width="150" class="mb-2">
  @endif
  <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
  @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Kembali</a>
