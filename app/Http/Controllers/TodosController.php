<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use Illuminate\Http\Request;
use App\Models\todos;

class TodosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $todos = todos::where(function ($query) use ($search) {
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
        todos::create([
            'name' => $request->name,
            'work' => $request->work,
            'due_date' => $request->duedate
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
        todos::find($id)->delete();
        return redirect(route("todo.index"));
    }
}
