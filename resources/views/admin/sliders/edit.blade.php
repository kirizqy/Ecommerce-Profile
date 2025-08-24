@extends('layouts.admin')

@section('title','Edit Slider')

@section('header')
  <div class="d-flex align-items-center gap-2">
    <h1 class="h4 m-0">Edit Slider</h1>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary ms-auto">
      <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
@endsection

@section('content')
  <div class="container-fluid">
    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      @include('admin.sliders.form', ['button' => 'Perbarui'])
    </form>
  </div>
@endsection
