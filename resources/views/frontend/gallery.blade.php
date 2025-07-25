@extends('layouts.frontend')

@section('title', 'Galeri Bina Bordir')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Gallery Bina Bordir</h1>
    <div class="row">
        @foreach ($gallery as $item)
        <div class="col-md-4 mb-4">
            <img src="{{ asset('storage/gallery/' . $item->image) }}" class="img-fluid rounded" alt="{{ $item->alt }}">
        </div>
        @endforeach
    </div>
</div>
@endsection
