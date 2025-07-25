@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Tambah Produk</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.products.partials.form', ['buttonText' => 'Simpan'])
    </form>
</div>
@endsection