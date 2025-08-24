@extends('layouts.admin')

@section('title','News')

@section('content')
<div class="container-fluid">

  {{-- Header + Search --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">News</h1>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
      <i class="fa fa-plus me-1"></i> Tambah
    </a>
  </div>

  <form method="get" class="mb-3">
    <div class="input-group" style="max-width:420px">
      <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Cari judul/konten...">
      <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
    </div>
  </form>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:90px">Gambar</th>
              <th>Judul</th>
              <th style="width:140px">Status</th>
              <th style="width:180px">Dipublish</th>
              <th style="width:120px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($news as $n)
              @php
                $img = $n->image ? asset('storage/'.$n->image) : asset('images/placeholder.webp');
              @endphp
              <tr>
                <td>
                  <img src="{{ $img }}" alt="{{ $n->title }}" style="width:64px;height:64px;object-fit:cover;border-radius:6px">
                </td>
                <td>
                  <div class="fw-semibold">{{ $n->title }}</div>
                  <div class="text-muted small">{{ $n->slug }}</div>
                </td>
                <td>
                  @if(($n->status ?? '') === 'published')
                    <span class="badge bg-success">Published</span>
                  @else
                    <span class="badge bg-secondary">Draft</span>
                  @endif
                </td>
                <td>
                  {{ optional($n->published_at)->format('d M Y H:i') ?? '—' }}
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.news.edit',$n) }}">
                    <i class="fa fa-pen"></i>
                  </a>
                  <form action="{{ route('admin.news.destroy',$n) }}" method="post" class="d-inline"
                        onsubmit="return confirm('Hapus berita ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit">
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">Belum ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if(method_exists($news,'links'))
      <div class="card-footer">
        {{ $news->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
