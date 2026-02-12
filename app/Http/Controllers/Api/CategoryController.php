<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Listar todas las categorías del usuario autenticado
     * GET /api/categories
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('tasks')
            ->latest()
            ->paginate(10);

        return response()->json($categories);
    }

    /**
     * Crear una nueva categoría
     * POST /api/categories
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ]
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.unique' => 'Ya tienes una categoría con este nombre',
            'name.max' => 'El nombre no puede exceder 255 caracteres'
        ]);

        $category = auth()->user()->categories()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'data' => $category
        ], 201);
    }

    /**
     * Mostrar una categoría específica
     * GET /api/categories/{category}
     */
    public function show(Category $category): JsonResponse
    {
        $category->load('tasks');

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Actualizar una categoría
     * PUT/PATCH /api/categories/{category}
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->ignore($category->id)
            ]
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.unique' => 'Ya tienes una categoría con este nombre',
            'name.max' => 'El nombre no puede exceder 255 caracteres'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente',
            'data' => $category
        ]);
    }

    /**
     * Eliminar una categoría
     * DELETE /api/categories/{category}
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($category->tasks()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una categoría con tareas asociadas'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }
}