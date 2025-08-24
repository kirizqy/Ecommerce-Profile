@extends('layouts.admin')
@section('title','Pesan')

@push('styles')
<style>
  .bb-chip-title{
    background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block
  }
  .table>:not(caption)>*>*{padding:.6rem .8rem}
  .table thead th{font-weight:600}
  .cell-message{max-width:480px}
</style>
@endpush

@section('header')
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div><span class="bb-chip-title">Pesan</span></div>
    <form method="GET" class="ms-auto">
      <div class="input-group" style="max-width:320px">
        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari nama/email/pesan...">
        <button class="btn btn-outline-secondary" type="submit">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </div>
    </form>
  </div>
@endsection

@section('content')
  @if(session('success'))
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
            <th style="width:180px">Nama</th>
            <th style="width:220px">Email</th>
            <th>Pesan</th>
            <th style="width:160px">Tanggal</th>
            <th style="width:120px" class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($contacts as $contact)
            <tr>
              <td class="fw-semibold">{{ $contact->name }}</td>
              <td>
                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                  {{ $contact->email }}
                </a>
              </td>
              <td class="cell-message">
                <span class="text-truncate d-inline-block w-100" title="{{ $contact->message }}">
                  {{ \Illuminate\Support\Str::limit($contact->message, 110) }}
                </span>
              </td>
              <td>{{ $contact->created_at?->format('d M Y H:i') }}</td>
              <td class="text-end">
                @if(Route::has('admin.contacts.show'))
                  <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-outline-secondary btn-sm" title="Lihat">
                    <i class="fas fa-eye"></i>
                  </a>
                @endif
                @if(Route::has('admin.contacts.destroy'))
                  <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
                        class="d-inline" onsubmit="return confirm('Hapus pesan ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" title="Hapus">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-5">Belum ada pesan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Laravel pagination --}}
    @if(method_exists($contacts, 'links'))
      <div class="card-footer d-flex justify-content-center">
        {{ $contacts->links() }}
      </div>
    @endif
  </div>
@endsection
