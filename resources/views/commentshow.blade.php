@extends('layouts.main')

@push('head')
    <title>Comments</title>
@endpush

@section('main-section')
    <div class="container my-5">
        <h2 class="mb-4 text-center text-primary">All Comments</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">
                @foreach ($comments as $comment)
                    <div class="card p-4 mb-4">
                        <p class="mb-3"><strong>Comment:</strong> {{ $comment->comment }}</p>

                        @if ($comment->image->isNotEmpty())
                            <div class="mb-3">
                                <strong>Images:</strong>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    @foreach ($comment->image as $image)
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Comment Image" class="img-thumbnail" width="100">
                                    @endforeach
                                </div>
                            </div>
                        @endif


                        <p class="text-muted mt-2">Posted on: {{ $comment->created_at->format('d M Y') }}</p>
                    </div>
                @endforeach

                <div class="text-center mt-4">
                    <a href="{{ route('images.index') }}" class="btn btn-outline-primary btn-lg">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
