@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pesanan</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Pesanan
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <table id="productTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Customer</th>
                    <th>Kontak</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Sumber</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_contact }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td><span class="badge badge-info text-uppercase">{{ $order->order_source }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                    <td>
                        @php
                        $statusColor = [
                        'pending' => 'secondary',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        ];
                        @endphp
                        <span class="badge badge-{{ $statusColor[$order->status] ?? 'secondary' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
    $(function() {
        $('#productTable').DataTable();
    });
</script>
@endpush