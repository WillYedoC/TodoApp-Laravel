<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    public function create(){
        return redirect()->route('tasks.index');
        }
    public function index()
    {
        $tasks = Task::with(['category', 'tags'])->paginate(10);
        Log::info($tasks);
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('tasks.index', compact('tasks', 'categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'is_completed' => 'boolean'
        ]);
        
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'is_completed' => $request->has('is_completed')
        ]);
        Log::info($validated);
        if (isset($validated['tags'])) {
            $task->tags()->attach($validated['tags']);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea creada exitosamente');
    }

    public function update(Request $request, Task $task)
    {

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'is_completed' => 'boolean'
        ]);
        Log::info($validated);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'is_completed' => $request->has('is_completed')
        ]);

        if (isset($validated['tags'])) {
            $task->tags()->sync($validated['tags']);
        } else {
            $task->tags()->detach();
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea actualizada exitosamente');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'Tarea eliminada exitosamente');
    }
}