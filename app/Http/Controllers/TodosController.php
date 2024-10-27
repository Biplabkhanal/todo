<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use Illuminate\Http\Request;
use App\Models\todos;

class TodosController extends Controller
{
    public function index()
    {
        $todos = todos::paginate(10); //fetch data from database
        $data = compact('todos');  //create array
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
        todos::create($request->only(['name', 'work', 'duedate']));

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
        $todo->update($request->only(['name', 'work', 'duedate']));
        return redirect()->route("todo.index");
    }

    public function destroy($id)
    {
        todos::find($id)->delete();
        return redirect(route("todo.index"));
    }
}
