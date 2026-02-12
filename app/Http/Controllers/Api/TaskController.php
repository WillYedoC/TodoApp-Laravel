<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Listar todas las tareas del usuario autenticado
     * GET /api/tasks
     */
    public function index(): JsonResponse
    {
        $tasks = Task::with(['category', 'tags'])
            ->latest()
            ->paginate(10);

        return response()->json($tasks);
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
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'tags' => 'nullable|array',
            'tags.*' => [
                'integer',
                Rule::exists('tags', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'is_completed' => 'boolean'
        ], [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede exceder 255 caracteres',
            'category_id.required' => 'La categoría es obligatoria',
            'category_id.exists' => 'La categoría seleccionada no existe o no te pertenece',
            'tags.array' => 'Las etiquetas deben ser un array',
            'tags.*.exists' => 'Una o más etiquetas no existen o no te pertenecen'
        ]);

        $task = auth()->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'is_completed' => $validated['is_completed'] ?? false
        ]);

        if (!empty($validated['tags'])) {
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
     * GET /api/tasks/{task}
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
     * PUT/PATCH /api/tasks/{task}
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'tags' => 'nullable|array',
            'tags.*' => [
                'integer',
                Rule::exists('tags', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'is_completed' => 'boolean'
        ], [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede exceder 255 caracteres',
            'category_id.exists' => 'La categoría seleccionada no existe o no te pertenece',
            'tags.*.exists' => 'Una o más etiquetas no existen o no te pertenecen'
        ]);

        $task->update($validated);

        if (array_key_exists('tags', $validated)) {
            $task->tags()->sync($validated['tags'] ?? []);
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
     * DELETE /api/tasks/{task}
     */
    public function destroy(Task $task): JsonResponse
    {
        Log::info('Eliminando tarea ID: ' . $task->id);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }
}
