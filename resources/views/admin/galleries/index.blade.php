@extends('layouts.admin')
@section('title', 'Gallery')

@section('content')
<div class="container-fluid py-3">

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <h5 class="mb-0">Our Gallery</h5>

    <div class="d-flex gap-2">
      <div class="input-group">
        <span class="input-group-text"><i class="fa fa-search"></i></span>
        <input type="search" id="js-filter" class="form-control" placeholder="Cari judul/deskripsi…">
      </div>
      <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-1"></i> Add
      </a>
    </div>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="row g-3" id="js-grid">
    @forelse($galleries as $g)
      @php
        $sizeText = null;
        try {
          if($g->media && Storage::disk('public')->exists($g->media)) {
            $bytes = Storage::disk('public')->size($g->media);
            $sizeText = $bytes >= 1073741824
              ? number_format($bytes/1073741824, 2).' GB'
              : ($bytes >= 1048576
                  ? number_format($bytes/1048576, 2).' MB'
                  : number_format($bytes/1024, 2).' KB');
          } elseif($g->image && Storage::disk('public')->exists($g->image)) {
            $bytes = Storage::disk('public')->size($g->image);
            $sizeText = $bytes >= 1073741824
              ? number_format($bytes/1073741824, 2).' GB'
              : ($bytes >= 1048576
                  ? number_format($bytes/1048576, 2).' MB'
                  : number_format($bytes/1024, 2).' KB');
          }
        } catch (\Throwable $e) {}
      @endphp

      <div class="col-md-4 js-item"
           data-title="{{ strtolower($g->title ?? '') }}"
           data-desc="{{ strtolower(\Illuminate\Support\Str::limit($g->description ?? '', 200)) }}">
        <div class="card h-100 shadow-sm">
          <div class="ratio ratio-16x9 bg-light">
            @if($g->is_video)
              <video controls poster="{{ $g->poster_url }}">
                <source src="{{ $g->url }}" type="{{ $g->mime_type }}">
              </video>
            @else
              <img src="{{ $g->url }}" class="w-100 h-100" style="object-fit:cover" alt="media">
            @endif
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div>
                <div class="fw-semibold mb-1">{{ $g->title ?: '—' }}</div>
                @if($g->description)
                  <div class="text-muted small">
                    {{ \Illuminate\Support\Str::limit($g->description, 100) }}
                  </div>
                @endif
              </div>
              <span class="badge {{ $g->is_video ? 'bg-dark' : 'bg-secondary' }}">
                {{ $g->is_video ? 'Video' : 'Image' }}
              </span>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-2 small text-muted">
              @if($sizeText)
                <span><i class="fa fa-database me-1"></i>{{ $sizeText }}</span>
              @endif
              @if($g->created_at)
                <span><i class="fa fa-calendar me-1"></i>{{ $g->created_at->format('d M Y') }}</span>
              @endif
            </div>
          </div>

          <div class="card-footer d-flex gap-2">
            <form action="{{ route('admin.galleries.destroy',$g->id) }}" method="POST"
                  onsubmit="return confirm('Delete this item?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">
                <i class="fa fa-trash me-1"></i>Delete
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info mb-0">No items yet.</div>
      </div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $galleries->links() }}
  </div>
</div>

<script>
(function(){
  const input = document.getElementById('js-filter');
  const items = Array.from(document.querySelectorAll('#js-grid .js-item'));
  if(!input) return;

  input.addEventListener('input', function(){
    const q = (this.value || '').trim().toLowerCase();
    if(!q){
      items.forEach(el => el.classList.remove('d-none'));
      return;
    }
    items.forEach(el => {
      const t = el.getAttribute('data-title') || '';
      const d = el.getAttribute('data-desc') || '';
      const hit = t.includes(q) || d.includes(q);
      el.classList.toggle('d-none', !hit);
    });
  });
})();
</script>
@endsection
