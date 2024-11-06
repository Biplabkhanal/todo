@extends('layouts.main')

@push('head')
    <title>Todo List App</title>
@endpush


@section('main-section')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>All Todos</h2>
            <a href="{{ route('todo.create') }}" class="btn btn-primary btn-lg">Add Todo</a>
        </div>
        <div class="d-flex mb-4">
            <form action="{{ route('todo.index') }}" method="get" class="d-flex w-100 gap-3">
                <input type="text" class="form-control" name='search' value="{{ $search ?? '' }}" id="search"
                    placeholder="Search todo by name">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Task Name</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($todos as $todo)
                            <tr valign="middle">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $todo->name }}</td>
                                <td>{{ $todo->work }}</td>
                                <td>{{ \Carbon\Carbon::parse($todo->duedate)->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('todo.show', $todo->id) }}" method="GET" class="d-inline">
                                        <button type="submit" class="btn btn-info btn-sm">View</button>
                                    </form>
                                    <a href="{{ route('todo.edit', $todo->id) }}" class="btn btn-warning btn-sm">Update</a>
                                    <form action="{{ route('todo.destroy', $todo->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this todo?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $todos->links() }}
            </div>
        </div>


        <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mt-5">
                <h3 class="mb-3">Add a Comment</h3>
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                    @error('comment')
                        <span class="text-danger">There must be some text in comment</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Attach an Image</label>
                    <input type="file" name="image" class="form-control" id="image">
                    @error('image')
                        <span class="text-danger">There must be some image in comment</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

        </form>

        {{-- <div class="mt-3 d-flex justify-content-center">
            <form action=" " method="GET" class="d-inline">
                <button type="submit" class="btn btn-info btn-lg">View Comments</button>
            </form>
        </div> --}}
        <div class="mt-3 d-flex justify-content-center">
            <a href="{{ route('images.index') }}" class="btn btn-info btn-lg">View Images</a>
        </div>

    </div>
@endsection
