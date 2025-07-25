@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <h4>Edit Slider</h4>
  <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.sliders.form', ['button' => 'Update'])
  </form>
</div>
@endsection