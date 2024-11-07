<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Image;
use Auth;
use Exception;
use Illuminate\Support\Facades\Request;
use DB;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        DB::beginTransaction();
        try {
            $comment = Comment::create([
                'user_id' => Auth::user()->id,
                'comment' => $request->comment,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('comment/images', 'public');
                $comment->image()->create([
                    'imageable_id' => $comment->id,
                    'imageable_type' => Comment::class,
                    'path' => $path
                ]);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->back()->with('error', 'Comment failed to add!');
        }
        CommentCreated::dispatch($comment);
        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function index()
    {
        $comments = Comment::with('image')->get();
        return view('commentshow', compact('comments'));
    }

    public function destroy(Request $request, $id)
    {
        try {
            $comment = Comment::with('user')->findOrFail($id);
        } catch (Exception $hello) {
            return redirect()->route('todo.index');
        }
        if ($comment->user?->id !== Auth::user()->id) {
            return redirect()->route('todo.index');
        }
        $comment->delete();
        return redirect()->route('todo.index');
    }


    public function restore($id)
    {
        try {
            $comment = Comment::with('user')->onlyTrashed()->findOrFail($id);
        } catch (Exception $hello) {
            return redirect()->route('todo.index');
        }
        if ($comment->user?->id !== Auth::user()->id) {
            return redirect()->route('todo.index');
        }
        $comment->restore();
        return redirect()->route('todo.index');
    }
}
