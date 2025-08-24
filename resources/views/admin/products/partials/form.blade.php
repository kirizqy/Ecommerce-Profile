{{-- resources/views/admin/products/partials/form.blade.php --}}
@php
  use Illuminate\Support\Facades\Storage;
  use App\Models\Product as ProductModel;

  $isEdit  = isset($product) && $product instanceof ProductModel && $product->exists;
  $oldImgs = $isEdit && is_array($product->images ?? []) ? $product->images : [];
  $cover   = $isEdit ? ($product->image ?? null) : null;

  $min = isset($min) ? (int)$min : 0;  // tanpa minimal
  $max = isset($max) ? (int)$max : 8;  // maksimal 8
@endphp

{{-- flags untuk JS --}}
<div id="bb-form-flags" data-is-edit="{{ $isEdit ? 1 : 0 }}" data-min="{{ $min }}" data-max="{{ $max }}"></div>

<div class="row g-3">
  <div class="col-md-8">
    <div class="mb-3">
      <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
      @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select" required>
          <option value="" disabled {{ old('category_id', $product->category_id ?? '')===''?'selected':'' }}>— pilih —</option>
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ (string)old('category_id', $product->category_id ?? '') === (string)$cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
        @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="col-sm-3">
        <label class="form-label">Harga <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price ?? 0) }}" required>
        @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="col-sm-3">
        <label class="form-label">Stok <span class="text-danger">*</span></label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mt-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
      @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mt-3 row g-3">
      <div class="col-sm-4">
        <label class="form-label">Link Shopee</label>
        <input type="url" name="shopee_link" class="form-control" value="{{ old('shopee_link', $product->shopee_link ?? '') }}">
        @error('shopee_link') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>
      <div class="col-sm-4">
        <label class="form-label">Link Tokopedia</label>
        <input type="url" name="tokopedia_link" class="form-control" value="{{ old('tokopedia_link', $product->tokopedia_link ?? '') }}">
        @error('tokopedia_link') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>
      <div class="col-sm-4">
        <label class="form-label">Link WhatsApp</label>
        <input type="url" name="whatsapp_link" class="form-control" value="{{ old('whatsapp_link', $product->whatsapp_link ?? '') }}">
        @error('whatsapp_link') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <label class="form-label d-flex align-items-center justify-content-between">
      <span>Gambar Produk (≤ {{ $max }})</span>
      <small class="text-muted">JPEG/PNG/WebP • ≤ 10MB</small>
    </label>

    <input
      type="file"
      name="images[]"
      id="images-input"
      class="form-control"
      multiple
      accept="image/jpeg,image/png,image/webp"
      aria-describedby="images-help"
    >
    <div id="images-help" class="form-text">
      Terpilih: <span id="file-counter">0 / {{ $max }}</span>. <strong>Pilih sekaligus</strong> (Ctrl/Shift).
    </div>

    @error('images')   <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

    {{-- Preview file baru yang dipilih (create & edit) --}}
    <div id="new-preview" class="mt-3 d-none">
      <div class="fw-semibold mb-2">Preview Upload</div>
      <div id="preview-grid" class="d-grid" style="grid-template-columns: repeat(3, 1fr); gap: .5rem;"></div>
    </div>

    {{-- Gambar yang sudah ada (khusus edit) --}}
    @if($isEdit)
      @php
        $existing = [];
        if ($cover) $existing[] = $cover;
        foreach ($oldImgs as $p) $existing[] = $p;
      @endphp
      @if (!empty($existing))
        <div class="mt-3">
          <div class="fw-semibold mb-2">Gambar Saat Ini</div>
          <div class="d-grid" style="grid-template-columns: repeat(3, 1fr); gap: .5rem;">
            @foreach ($existing as $idx => $p)
              @php
                $isCover = $idx === 0;
                // normalisasi path relatif (buang public/ & storage/ kalau ada)
                $raw = ltrim(preg_replace('#^(public/)?(storage/)?#', '', (string)$p) ?? '', '/');

                $url = \Illuminate\Support\Str::startsWith($p, ['http://','https://'])
                        ? $p
                        : Storage::url($raw);  // → /storage/products/xxx.jpg
              @endphp
              <div class="position-relative border rounded overflow-hidden">
                <img src="{{ $url }}" alt="img" class="w-100" style="aspect-ratio:1/1; object-fit:cover;">
                <div class="p-1 small {{ $isCover ? 'bg-primary text-white' : 'bg-light' }}">
                  {{ $isCover ? 'Cover' : 'Galeri' }}
                </div>
                @unless($isCover)
                  <label class="form-check m-1 position-absolute top-0 end-0 bg-white px-1 rounded shadow-sm">
                    <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $p }}">
                    <span class="small">Hapus</span>
                  </label>
                @endunless
              </div>
            @endforeach
          </div>
          <div class="form-text mt-1">Jumlah akhir maksimal {{ $max }} gambar.</div>
        </div>
      @endif
    @endif
  </div>
</div>

@push('scripts')
<script>
(function () {
  const flags   = document.getElementById('bb-form-flags');
  const isEdit  = !!(flags && flags.dataset.isEdit === '1');
  const minSel  = parseInt(flags?.dataset.min ?? '0', 10);
  const maxSel  = parseInt(flags?.dataset.max ?? '8', 10);

  const input       = document.getElementById('images-input');
  const previewWrap = document.getElementById('new-preview');
  const grid        = document.getElementById('preview-grid');
  const counterEl   = document.getElementById('file-counter');
  const form        = document.getElementById('product-form') || input?.closest('form');

  if (!input || !grid) return;

  const MAX_BYTES   = 10 * 1024 * 1024; // 10MB
  const isValidType = (f) => /^image\/(jpeg|png|webp)$/i.test(f.type);

  function setCounter(n) { if (counterEl) counterEl.textContent = `${n} / ${maxSel}`; }

  function render() {
    const files = input.files ? Array.from(input.files) : [];
    grid.innerHTML = '';
    previewWrap && previewWrap.classList.toggle('d-none', files.length === 0);
    setCounter(files.length);

    files.forEach((file) => {
      const url = URL.createObjectURL(file);
      const item = document.createElement('div');
      item.innerHTML = `
        <img src="${url}" alt="preview"
             style="width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:.5rem;">
      `;
      grid.appendChild(item);
    });
  }

  input.addEventListener('change', () => {
    const files = input.files ? Array.from(input.files) : [];

    if (files.length > maxSel) {
      alert(`Maksimum ${maxSel} gambar.`);
      input.value = '';
      render();
      return;
    }

    const bad = files.find(f => !isValidType(f) || f.size > MAX_BYTES);
    if (bad) {
      alert('Ada file yang tidak valid (harus JPEG/PNG/WebP dan ≤ 10MB).');
      input.value = '';
    }

    render();
  });

  form && form.addEventListener('submit', (e) => {
    const total = input.files ? input.files.length : 0;
    if (total > maxSel) {
      e.preventDefault(); alert(`Maksimum ${maxSel} gambar.`); return;
    }
    if (!isEdit && minSel > 0 && total < minSel) {
      e.preventDefault(); alert(`Minimal ${minSel} gambar.`); return;
    }
  });

  render();
})();
</script>
@endpush
