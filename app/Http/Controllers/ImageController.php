<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\todos;


class ImageController extends Controller
{
    public function index()
    {

        $todos = todos::with('image')->get()->pluck('image')->flatten();
        $comments = Comment::with('image')->get()->pluck('image')->flatten();
        return view('images', compact('todos', 'comments',));
    }
}
