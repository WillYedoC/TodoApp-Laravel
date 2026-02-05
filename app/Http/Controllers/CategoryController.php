<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        $categories = Category::withCount('tasks')->with('tasks')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name'
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    // Mostrar una categoría
    public function show(Category $category)
    {
        $category->load('tasks');
        return view('categories.show', compact('category'));
    }

    // Mostrar formulario de edición
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Actualizar categoría
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    // Eliminar categoría
    public function destroy(Category $category)
    {
        // Verificar si tiene tareas asociadas
        if ($category->tasks()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'No se puede eliminar una categoría con tareas asociadas');
        }

        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}