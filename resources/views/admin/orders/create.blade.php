@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Tambah Pesanan</h1>
    <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.orders.partials.form', ['buttonText' => 'Simpan'])
    </form>
</div>
@endsection