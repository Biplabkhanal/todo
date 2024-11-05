@extends('layouts.main')

@section('main-section')
<div class="container mt-5">
    <h5 class="text-center mb-4">Details for <span class="text-primary">{{ $todo->name }}</span></h5>

    <div class="row justify-content-center">
        <div class="col-5">
            <div class="card p-4 mb-4">
                <p class="mb-3"><strong>Description:</strong> {{ $todo->work }}</p>
                <p class="mb-3"><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($todo->duedate)->format('d M Y') }}</p>

                <div class="mb-3">
                    <strong>Images:</strong>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        @foreach ($todo->image as $image)
                        <img src="{{ Str::startsWith($image->path, 'https://') ? $image->path : asset('storage/' . $image->path) }}" alt="Image" class="img-thumbnail" width="100">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('todo.index') }}" class="btn btn-outline-primary btn-lg">Back</a>
    </div>
</div>
@endsection
