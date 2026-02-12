<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AuthController;

// Ruta de información de la API
Route::get('/', function () {
    return response()->json([
        'message' => 'API de Gestión de Tareas',
        'authentication' => [
            'register' => 'POST /api/register',
            'login' => 'POST /api/login',
            'logout' => 'POST /api/logout ',
            'user' => 'GET /api/user ',
        ],
        'endpoints' => [
            'tasks' => [
                'index' => 'GET /api/tasks ',
                'store' => 'POST /api/tasks ',
                'show' => 'GET /api/tasks/{id} ',
                'update' => 'PUT /api/tasks/{id} ',
                'destroy' => 'DELETE /api/tasks/{id} ',
            ],
            'categories' => [
                'index' => 'GET /api/categories ',
                'store' => 'POST /api/categories ',
                'show' => 'GET /api/categories/{id} ',
                'update' => 'PUT /api/categories/{id} ',
                'destroy' => 'DELETE /api/categories/{id} ',
            ],
            'tags' => [
                'index' => 'GET /api/tags ',
                'store' => 'POST /api/tags ',
                'show' => 'GET /api/tags/{id} ',
                'update' => 'PUT /api/tags/{id} ',
                'destroy' => 'DELETE /api/tags/{id} ',
            ]
        ]
    ]);
});

// Rutas de autenticación 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
});