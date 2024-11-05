@extends('layouts.main')
@push('head')
    <title>Update Todo</title>
@endpush

@section('main-section')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-5">
            <div class="h2">Update Todo</div>
            <a href="{{ route('todo.index') }}" class="btn btn-outline-primary btn-lg">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('todo.update', $todo->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label for="" class="form-label mt-4">Task Name</label>
                    <input type="text" name="name" class = "form-control" id="" value="{{ $todo->name }}">
                    <label for="" class="form-label mt-4">Description</label>
                    <input type="text" name="work" class = "form-control" id="" value="{{ $todo->work }}">
                    <label for="" class="form-label mt-4">Due Date</label>
                    <input type="date" name="duedate" class = "form-control" id=""
                        value="{{ $todo->due_date }}">

                    <label for="file" class="form-label mt-4">Upload Files</label>
                    <input type="file" name="image[]" class="form-control" id="" multiple>

                    <button class="btn btn-primary btn-lg mt-4" type="submit">Update Todo</button>
                </form>
            </div>
        </div>
    </div>
@endsection
