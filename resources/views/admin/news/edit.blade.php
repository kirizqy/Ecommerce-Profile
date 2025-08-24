@extends('layouts.admin')
@section('title','Edit News')

@section('content')
<div class="container-fluid">
  <h1 class="h4 mb-3">Edit News</h1>
  <form action="{{ route('admin.news.update',$news) }}" method="post" enctype="multipart/form-data" class="card">
    @csrf @method('PUT')
    <div class="card-body">
      @include('admin.news.partials.form', ['news' => $news])
    </div>
    <div class="card-footer d-flex gap-2">
      <a href="{{ route('admin.news.index') }}" class="btn btn-light">Batal</a>
      <button class="btn btn-primary" type="submit">Update</button>
    </div>
  </form>
</div>
@endsection
