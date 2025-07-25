@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Edit Pesanan</h1>
    <form action="{{ route('admin.orders.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.orders.partials.form', ['buttonText' => 'Update', 'product' => $product])
    </form>
</div>
@endsection