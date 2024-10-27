<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodosController;

Route::resource('todo', TodosController::class);
