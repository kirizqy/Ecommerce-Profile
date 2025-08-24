@extends('layouts.admin')

@section('title','Tambah Slider')

@section('header')
  <div class="d-flex align-items-center gap-2">
    <h1 class="h4 m-0">Tambah Slider</h1>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary ms-auto">
      <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
@endsection

@section('content')
  <div class="container-fluid">
    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @include('admin.sliders.form', ['button' => 'Simpan'])
    </form>
  </div>
@endsection
