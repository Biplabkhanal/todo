<?php

namespace App\Http\Controllers;

use App\Services\ImageServices\CheckTodoService;
use App\Models\Image;
use App\Models\todos;


class ImageController extends Controller
{
    public function index()
    {
    $images=Image::with('imageable')->paginate(8);
   $images= CheckTodoService::checkTodo($images);
    return view('images',compact('images'));
    }
}
