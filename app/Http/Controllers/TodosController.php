<?php

namespace App\Http\Controllers;

use App\Models\todos;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Storage;

class TodosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $todos = todos::with('image')->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('work', 'like', "%$search%");
        })->latest()->paginate(10);
        $data = compact('todos');
        if ($search) {
            $data = array_merge($data, compact('search'));
        }
        return view("welcome")->with($data);
    }

    public function show($id)
    {
        // return view('create');
    }

    public function create()
    {
        return view('create');
    }

    public function store(TodoRequest $request)
    {
        $todo = todos::create([
            'name' => $request->name,
            'work' => $request->work,
            'due_date' => $request->duedate
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
        }

        $todo->image()->create([
            'todo_id' => $todo->id,
            'path' => $path
        ]);
        return redirect(route("todo.index"));
    }

    public function edit($id)
    {
        $todo = todos::find($id);
        $data = compact('todo');
        return view("update")->with($data);
    }

    public function update(TodoRequest $request, $id)
    {
        $todo = todos::find($id);
        $todo->update([
            'name' => $request->name,
            'work' => $request->work,
            'due_date' => $request->duedate
        ]);
        return redirect()->route("todo.index");
    }

    public function destroy($id)
    {

        $todo =  todos::with('image')->findOrFail($id);
        $image_path = $todo->image->path;
        if ($image_path) {
            Storage::disk('public')->delete($image_path);
        }
        $todo->delete();
        return redirect(route("todo.index"));
    }
}
