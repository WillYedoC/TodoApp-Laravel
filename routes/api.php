<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Ruta de información de la API
Route::get('/', function () {
    return response()->json([
        'message' => 'API de Gestión de Tareas',
        'endpoints' => [
            'tasks' => [
                'index' => 'GET /api/tasks',
                'store' => 'POST /api/tasks',
                'show' => 'GET /api/tasks/{id}',
                'update' => 'PUT /api/tasks/{id}',
                'destroy' => 'DELETE /api/tasks/{id}',
            ],
            'categories' => [
                'index' => 'GET /api/categories',
                'store' => 'POST /api/categories',
                'show' => 'GET /api/categories/{id}',
                'update' => 'PUT /api/categories/{id}',
                'destroy' => 'DELETE /api/categories/{id}',
            ],
            'tags' => [
                'index' => 'GET /api/tags',
                'store' => 'POST /api/tags',
                'show' => 'GET /api/tags/{id}',
                'update' => 'PUT /api/tags/{id}',
                'destroy' => 'DELETE /api/tags/{id}',
            ]
        ]
    ]);
});

Route::apiResource('tasks', TaskController::class);

Route::apiResource('categories', CategoryController::class);

Route::apiResource('tags', TagController::class);