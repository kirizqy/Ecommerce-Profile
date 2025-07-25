@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">{{ isset($order) ? 'Edit Pesanan' : 'Tambah Pesanan' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($order) ? route('admin.orders.update', $order->id) : route('admin.orders.store') }}" method="POST">
                @csrf
                @if(isset($order))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label>Nama Customer</label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                        value="{{ old('customer_name', $order->customer_name ?? '') }}" required>
                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Kontak (WA/Email)</label>
                    <input type="text" name="customer_contact" class="form-control @error('customer_contact') is-invalid @enderror"
                        value="{{ old('customer_contact', $order->customer_contact ?? '') }}" required>
                    @error('customer_contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ old('product_id', $order->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', $order->quantity ?? 1) }}" min="1" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Sumber Pesanan</label>
                    <select name="order_source" class="form-control" required>
                        <option value="shopee" {{ old('order_source', $order->order_source ?? '') == 'shopee' ? 'selected' : '' }}>Shopee</option>
                        <option value="tokopedia" {{ old('order_source', $order->order_source ?? '') == 'tokopedia' ? 'selected' : '' }}>Tokopedia</option>
                        <option value="whatsapp" {{ old('order_source', $order->order_source ?? '') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Pesan</label>
                    <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror"
                        value="{{ old('order_date', isset($order->order_date) ? $order->order_date->format('Y-m-d') : '') }}" required>
                    @error('order_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diproses" {{ old('status', $order->status ?? '') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ old('status', $order->status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status', $order->status ?? '') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $order->notes ?? '') }}</textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success">{{ isset($order) ? 'Update' : 'Simpan' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
