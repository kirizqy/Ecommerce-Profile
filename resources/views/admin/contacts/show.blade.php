@extends('layouts.admin')
@section('title','Detail Pesan')

@push('styles')
<style>
  .bb-chip-title{background:#212529;color:#fff;padding:.4rem .8rem;border-radius:.75rem;font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12);display:inline-block}
  .kv th{width:160px;white-space:nowrap}
  .message-box{white-space:pre-line}
</style>
@endpush

@section('header')
  <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
    <div><span class="bb-chip-title">Detail Pesan</span></div>

    <div class="ms-auto d-flex gap-2">
      <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
      </a>
      @if (Route::has('admin.contacts.destroy'))
        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
              onsubmit="return confirm('Hapus pesan ini?')">
          @csrf @method('DELETE')
          <button class="btn btn-outline-danger">
            <i class="fa-solid fa-trash me-1"></i> Hapus
          </button>
        </form>
      @endif
    </div>
  </div>
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table kv mb-0">
        <tr>
          <th>Nama</th>
          <td class="fw-semibold">{{ $contact->name }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td>
            @if($contact->email)
              <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
            @else
              -
            @endif
          </td>
        </tr>
        <tr>
          <th>Telepon</th>
          <td>{{ $contact->phone ?? '-' }}</td>
        </tr>
        <tr>
          <th>Tanggal</th>
          <td>{{ optional($contact->created_at)->format('d M Y H:i') }}</td>
        </tr>
        <tr>
          <th>Pesan</th>
          <td class="message-box">{{ $contact->message }}</td>
        </tr>
      </table>
    </div>
  </div>
@endsection
