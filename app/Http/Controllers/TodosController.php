<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Comment;
use App\Models\Image;
use App\Models\todos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TodosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $todos = todos::with('image')->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('work', 'like', "%$search%");
        })->latest()->paginate(10);
        $comments = Comment::with('image')->get();
        $data = compact('todos', 'comments');
        if ($search) {
            $data = array_merge($data, compact('search'));
        }
        return view("welcome")->with($data);
    }

    public function show($id)
    {
        $todo = todos::with('image')->findOrFail($id);
        return view('show', compact('todo'));
    }


    public function create()
    {
        return view('create');
    }

    public function store(TodoRequest $request)
    {
        // dd($request);
         $todo = todos::create([
            'name' => $request->name,
            'work' => $request->work,
            'due_date' => $request->duedate
        ]);
        $path = null;
        if ($request->image) {
            foreach ($request->file('image') as $file){
            $path = $file->store('images', 'public');
            $todo->image()->create([
                'imageable_id'=>$todo->id,
                'imageable_type'=>todos::class,
                'path' => $path
            ]);
        }
    }

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

        foreach ($todo->image as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

    if ($request->image) {
        foreach ($request->file('image') as $file) {
            $path = $file->store('images', 'public');
            $todo->image()->create([
                'imageable_id'=>$todo->id,
                'imageable_type'=>todos::class,
                'path' => $path]
            );
        }
    }


        return redirect()->route("todo.index");
    }

    public function destroy($id)
    {

        $todo =  todos::with('image')->findOrFail($id);
        foreach ($todo->image as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $todo->delete();
        return redirect(route("todo.index"));
    }
}
