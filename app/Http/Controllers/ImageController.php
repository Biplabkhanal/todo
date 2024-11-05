<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\todos;


class ImageController extends Controller
{
    public function index()
    {

        $todoImages = todos::with('image')->get()->pluck('image')->flatten();
        $commentImages = Comment::with('image')->get()->pluck('image')->flatten();

        return view('images', compact('todoImages', 'commentImages'));
    }
}
