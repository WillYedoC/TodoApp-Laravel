<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('task', TaskController::class);
Route::resource('category', CategoryController::class);
Route::resource('tag', TagController::class);