@extends('layouts.admin')
@section('title','Slider')

@push('styles')
<style>
  .bb-chip-title{
    background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block
  }
  .table>:not(caption)>*>*{padding:.6rem .8rem}
  .table thead th{font-weight:600}
  .slider-thumb{width:72px;height:48px;object-fit:cover;border-radius:6px}
</style>
@endpush

{{-- Header: chip kiri + tombol kanan --}}
@section('header')
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div><span class="bb-chip-title">Slider</span></div>
    <div class="d-flex gap-2 ms-auto">
      @if(Route::has('admin.sliders.create'))
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
          <i class="fa-solid fa-plus me-1"></i> Tambah Slider
        </a>
      @endif
    </div>
  </div>
@endsection

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:90px">Gambar</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th class="text-center">Urutan</th>
            <th class="text-center">Status</th>
            <th style="width:160px" class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($sliders as $s)
            <tr>
              <td>
                @php
                  $url = $s->image ? asset('storage/'.$s->image) : 'https://placehold.co/72x48?text=No+Img';
                @endphp
                <img src="{{ $url }}" alt="thumb" class="slider-thumb"
                     onerror="this.onerror=null;this.src='https://placehold.co/72x48?text=No+Img';">
              </td>
              <td class="fw-semibold">{{ $s->title ?? '-' }}</td>
              <td class="text-muted">{{ \Illuminate\Support\Str::limit($s->description, 60) }}</td>
              <td class="text-center">{{ $s->order }}</td>
              <td class="text-center">
                @if($s->status)
                  <span class="badge bg-success">Aktif</span>
                @else
                  <span class="badge bg-secondary">Nonaktif</span>
                @endif
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  @if(Route::has('admin.sliders.edit'))
                    <a href="{{ route('admin.sliders.edit', $s) }}" class="btn btn-outline-primary" title="Edit">
                      <i class="fa-solid fa-pen"></i>
                    </a>
                  @endif
                  @if(Route::has('admin.sliders.destroy'))
                    <form action="{{ route('admin.sliders.destroy', $s) }}" method="POST"
                          onsubmit="return confirm('Hapus slider ini?')" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-danger" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-5">Belum ada slider.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if (method_exists($sliders,'links'))
      <div class="card-footer d-flex justify-content-center">
        {{ $sliders->links() }}
      </div>
    @endif
  </div>
@endsection
