@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Kategori</h3>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
          <i class="fas fa-plus"></i> Tambah kategori
        </a>
      </div>

      <table class="table table-bordered table-striped" id="categoryTable">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Slug</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $key => $category)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td>
              <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">Belum ada kategori</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('#categoryTable').DataTable();
  });
</script>
@endpush