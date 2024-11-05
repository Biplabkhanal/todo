@extends('layouts.main')

@push('head')
    <title>Image Gallery</title>
    <style>
        .gallery-image {
            cursor: pointer;
            height: 100% !important;
        }
    </style>
@endpush

@section('main-section')
    <div class="container my-5">
        <h2 class="mb-4 text-center text-primary">All Images</h2>

        <div class="row">
            @foreach ($todos as $todo)
                <div class="col-md-3 col-sm-4 mb-4 d-flex justify-content-center">
                    <a href="{{ route('todo.show', $todo->id) }}">
                        <img src="{{ asset('storage/' . $todo->path) }}" alt="Todo Image" class="img-thumbnail gallery-image">
                    </a>
                </div>
            @endforeach

            @foreach ($comments as $comment)
                <div class="col-md-3 col-sm-4 mb-4 d-flex justify-content-center">
                    <a href="{{ route('comments.index', $comment->id) }}">
                    <img src="{{ asset('storage/' . $comment->path) }}" alt="Comment Image" class="img-thumbnail gallery-image">
                    </a>
                </div>

            @endforeach

        </div>


        <div class="text-center mt-4">
            <a href="{{ route('todo.index') }}" class="btn btn-outline-primary btn-lg">Back</a>
        </div>

    </div>
@endsection
