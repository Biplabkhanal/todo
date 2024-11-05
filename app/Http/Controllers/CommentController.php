<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $comment = Comment::create([
            'comment' => $request->comment,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('comment/images', 'public');

            $comment->image()->create([
                'path' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function index()
    {
        $comments = Comment::with('image')->latest()->paginate(10);
        return view('commentshow', compact('comments'));
    }
}


