<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodosController;


Route::resource('todo', TodosController::class);
Route::resource('comments', CommentController::class);
Route::resource('images', ImageController::class);


