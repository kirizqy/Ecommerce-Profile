@extends('layouts.admin')
@section('title','Kategori')

@push('styles')
<style>
  .bb-chip-title{
    background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block
  }
  .table>:not(caption)>*>*{padding:.6rem .8rem}
  .table thead th{font-weight:600}
</style>
@endpush

{{-- Header --}}
@section('header')
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div><span class="bb-chip-title">Kategori</span></div>
    <div class="d-flex gap-2 ms-auto">
      @if (Route::has('admin.categories.create'))
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
          <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
        </a>
      @endif
    </div>
  </div>
@endsection

@section('content')
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa-regular fa-circle-check me-1"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:90px">No</th>
            <th>Nama</th>
            <th style="width:220px">Dibuat</th>
            <th style="width:160px" class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $c)
            <tr>
              <td>#{{ $loop->iteration }}</td>
              <td class="fw-semibold">{{ $c->name }}</td>
              <td>{{ $c->created_at?->format('d M Y H:i') }}</td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-2">
                  @if (Route::has('admin.categories.edit'))
                    <a href="{{ route('admin.categories.edit',$c) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                      <i class="fa-solid fa-pen"></i>
                    </a>
                  @endif
                  @if (Route::has('admin.categories.destroy'))
                    <form action="{{ route('admin.categories.destroy',$c) }}" method="POST"
                          onsubmit="return confirm('Hapus kategori ini?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-5">Belum ada kategori.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if (method_exists($categories,'links'))
      <div class="card-footer d-flex justify-content-center">
        {{ $categories->links() }}
      </div>
    @endif
  </div>
@endsection
