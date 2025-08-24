<div class="p-3 p-md-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="h4 fw-bold mb-0">{{ $item->title }}</h1>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <div class="text-muted mb-3">{{ $item->published_at?->format('d M Y H:i') }}</div>

  @if(!empty($item->image_url))
    <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
         class="img-fluid rounded shadow-sm mb-3"
         onerror="this.src='{{ $placeholder }}'">
  @endif

  <article class="mb-1">{!! nl2br(e($item->body)) !!}</article>
</div>
