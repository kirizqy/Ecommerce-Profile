@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <h4>Tambah Slider Baru</h4>
  <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.sliders.form', ['button' => 'Simpan'])
  </form>
</div>
@endsection