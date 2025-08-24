@foreach($galleries as $gallery)
  @php
    $title = $gallery->title ?: 'Gallery Item';
  @endphp
  <div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100">
      <div class="ratio ratio-1x1">
        @if($gallery->is_video)
          <video
            class="w-100 h-100 obj-cover"
            preload="metadata"
            controls
            playsinline
            muted
            title="{{ $title }}"
            poster="{{ $gallery->poster_url }}"
          >
            <source src="{{ $gallery->url }}" type="{{ $gallery->mime_type }}">
            Browser Anda tidak mendukung pemutaran video.
          </video>
        @else
          <img
            src="{{ $gallery->url }}"
            class="w-100 h-100 obj-cover"
            alt="{{ $title }}"
            loading="lazy" width="600" height="600"
            onerror="this.onerror=null;this.src='{{ $placeholder }}';"
          >
        @endif
      </div>
      <div class="card-body">
        <h6 class="card-title mb-0 text-truncate" title="{{ $title }}">{{ $title }}</h6>
      </div>
    </div>
  </div>
@endforeach