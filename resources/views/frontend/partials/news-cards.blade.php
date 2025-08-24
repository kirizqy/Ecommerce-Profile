@php
  use Illuminate\Support\Facades\Route;
@endphp

@foreach($posts as $post)
  <div class="col-12 col-sm-6 col-lg-4" data-item="news-card">
    <div class="card h-100 shadow-sm border-0">
      <div class="ratio ratio-16x9 bg-light">
        <img
          src="{{ $post->image_url ?? $placeholder }}"
          alt="{{ $post->title }}"
          loading="lazy"
          style="object-fit:cover;"
          onerror="this.src='{{ $placeholder }}'">
      </div>

      <div class="card-body">
        <div class="text-muted small mb-1">
          {{ optional($post->published_at)->format('d M Y H:i') }}
        </div>

        <h5 class="card-title mb-2">
          @php
            $href = route('news.show', $post->slug);
          @endphp
          <a href="{{ $href }}"
             data-href="{{ $href }}"
             class="js-news-link stretched-link text-decoration-none">
            {{ $post->title }}
          </a>
        </h5>
      </div>
    </div>
  </div>
@endforeach
