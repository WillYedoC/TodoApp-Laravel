<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Listar todas las etiquetas del usuario autenticado
     * GET /api/tags
     */
    public function index(): JsonResponse
    {
        $tags = Tag::withCount('tasks')
            ->latest()
            ->paginate(10);

        return response()->json($tags);
    }

    /**
     * Crear una nueva etiqueta
     * POST /api/tags
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ]
        ], [
            'name.required' => 'El nombre de la etiqueta es obligatorio',
            'name.unique' => 'Ya tienes una etiqueta con este nombre',
            'name.max' => 'El nombre no puede exceder 255 caracteres'
        ]);

        $tag = auth()->user()->tags()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Etiqueta creada exitosamente',
            'data' => $tag
        ], 201);
    }

    /**
     * Mostrar una etiqueta específica
     * GET /api/tags/{tag}
     */
    public function show(Tag $tag): JsonResponse
    {
        $tag->load('tasks');

        return response()->json([
            'success' => true,
            'data' => $tag
        ]);
    }

    /**
     * Actualizar una etiqueta
     * PUT/PATCH /api/tags/{tag}
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->ignore($tag->id)
            ]
        ], [
            'name.required' => 'El nombre de la etiqueta es obligatorio',
            'name.unique' => 'Ya tienes una etiqueta con este nombre',
            'name.max' => 'El nombre no puede exceder 255 caracteres'
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
     * DELETE /api/tags/{tag}
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->tasks()->detach();
        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Etiqueta eliminada exitosamente'
        ]);
    }
}