@extends('\layouts.main') <!-- Extending layout\main -->

@push('head')
<title>Todo List App</title>
@endpush

@section('main-section')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Todos</h2>
        <a href="{{ route('todo.create') }}" class="btn btn-primary btn-lg">Add Todo</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody >
                    @foreach($todos as $todo)
                        <tr valign="middle">
                            <td>{{ $todo->name }}</td>
                            <td>{{ $todo->work }}</td>
                            <td>{{ \Carbon\Carbon::parse($todo->duedate)->format('d M Y') }}</td> <!-- Format date -->
                            <td>
                                <a href="{{ route('todo.edit', $todo->id) }}" class="btn btn-warning btn-sm">Update</a>
                                <a href="{{ route('todo.delete', $todo->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this todo?');">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
