@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Edit Kategori</h1>
    <form action="{{ route('admin.categories.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.categories.partials.form', ['buttonText' => 'Update', 'product' => $product])
    </form>
</div>
@endsection