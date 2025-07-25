@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Edit Produk</h1>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products.partials.form', ['buttonText' => 'Update', 'product' => $product])
    </form>
</div>
@endsection