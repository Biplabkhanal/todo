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
                            <th>Task Name</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($todos as $todo)
                            <tr valign="middle">
                                <td>{{ $todo->name }}</td>
                                <td>{{ $todo->work }}</td>
                                <td>{{ \Carbon\Carbon::parse($todo->duedate)->format('d M Y') }}</td> <!-- Format date -->
                                <td>
                                    @if ($todo->image)
                                        <img src="{{ asset('storage/' . $todo->image->path) }}" alt="Image"
                                            width="100">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
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
    </div>
@endsection
