<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tasks')->with('tasks')->orderBy('created_at', 'desc')->paginate(10);
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name'
        ]);

        Category::create($validated);

        return redirect()->route('category.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function show(Category $category)
    {
        $category->load('tasks');
        return view('category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update($validated);

        return redirect()->route('category.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(Category $category)
    {
        if ($category->tasks()->count() > 0) {
            return redirect()->route('category.index')
                ->with('error', 'No se puede eliminar una categoría con tareas asociadas');
        }

        $category->delete();
        
        return redirect()->route('category.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}