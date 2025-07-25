@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Tambah Kategori</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.categories.partials.form', ['buttonText' => 'Simpan'])
    </form>
</div>
@endsection