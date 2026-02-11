<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('tasks')->with('tasks')->orderBy('created_at', 'desc')->paginate(10);
        return view('tag.index', compact('tags'));
    }

    public function create()
    {
        return view('tag.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:tags,name'
        ]);

        Tag::create($validated);

        return redirect()->route('tag.index')
            ->with('success', 'Etiqueta creada exitosamente');
    }

    public function show(Tag $tag)
    {
        $tag->load('tasks');
        return view('tag.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        return view('tag.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:tags,name,' . $tag->id
        ]);

        $tag->update($validated);

        return redirect()->route('tag.index')
            ->with('success', 'Etiqueta actualizada exitosamente');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        
        return redirect()->route('tag.index')
            ->with('success', 'Etiqueta eliminada exitosamente');
    }
}