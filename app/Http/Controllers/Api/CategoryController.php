<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Listar todas las categorías
     * GET /api/categories
     */
    public function index(): JsonResponse
    {

        $categories = Category::withCount('tasks')
            ->latest() 
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Crear una nueva categoría
     * POST /api/categories
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'data' => $category
        ], 201);
    }

    /**
     * Mostrar una categoría específica
     * GET /api/categories/{id}
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
     * PUT /api/categories/{id}
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
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
     * DELETE /api/categories/{id}
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