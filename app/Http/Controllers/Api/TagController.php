<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
     * Listar todas las etiquetas
     * GET /api/tags
     */
    public function index(): JsonResponse
    {
        $tags = Tag::withCount('tasks')->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    /**
     * Crear una nueva etiqueta
     * POST /api/tags
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name'
        ]);

        $tag = Tag::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Etiqueta creada exitosamente',
            'data' => $tag
        ], 201);
    }

    /**
     * Mostrar una etiqueta específica
     * GET /api/tags/{id}
     */
    public function show($id): JsonResponse
    {
        $tag = Tag::withCount('tasks')->find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Etiqueta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tag
        ]);
    }

    /**
     * Actualizar una etiqueta
     * PUT /api/tags/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Etiqueta no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id
        ]);

        $tag->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Etiqueta actualizada exitosamente',
            'data' => $tag
        ]);
    }

    /**
     * Eliminar una etiqueta
     * DELETE /api/tags/{id}
     */
    public function destroy($id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Etiqueta no encontrada'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Etiqueta eliminada exitosamente'
        ]);
    }
}