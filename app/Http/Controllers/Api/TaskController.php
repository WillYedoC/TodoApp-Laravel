<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Listar todas las tareas
     * GET /api/tasks
     */
    public function index(): JsonResponse
    {
        $tasks = Task::with(['category', 'tags'])
            ->latest() 
            ->paginate(10); 

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Crear una nueva tarea
     * POST /api/tasks
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_completed' => 'boolean'
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'is_completed' => $validated['is_completed'] ?? false
        ]);

        if (isset($validated['tags'])) {
            $task->tags()->attach($validated['tags']);
        }

        $task->load(['category', 'tags']);

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente',
            'data' => $task
        ], 201);
    }

    /**
     * Mostrar una tarea específica
     * GET /api/tasks/{id}
     */
    public function show(Task $task): JsonResponse
    {
        $task->load(['category', 'tags']);

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    /**
     * Actualizar una tarea
     * PUT/PATCH /api/tasks/{id}
     */
    public function update(Request $request, Task $task): JsonResponse
    {

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_completed' => 'boolean'
        ]);

        $task->update([
            'title' => $validated['title'] ?? $task->title,
            'description' => $validated['description'] ?? $task->description,
            'category_id' => $validated['category_id'] ?? $task->category_id,
            'is_completed' => $validated['is_completed'] ?? $task->is_completed
        ]);

        if (isset($validated['tags'])) {
            $task->tags()->sync($validated['tags']);
        }

        $task->load(['category', 'tags']);

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente',
            'data' => $task
        ]);
    }

    /**
     * Eliminar una tarea
     * DELETE /api/tasks/{id}
     */
    public function destroy(Task $task): JsonResponse
    {
        
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }
}