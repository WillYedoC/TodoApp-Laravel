<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear categorías
        $categories = [
            'Trabajo',
            'Personal',
            'Estudios',
            'Hogar',
            'Salud'
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        // Crear etiquetas
        $tags = [
            'Urgente',
            'Importante',
            'Corto plazo',
            'Largo plazo',
            'Planificación',
            'Revisión'
        ];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }

        // Crear tareas de ejemplo
        $allTags = Tag::all();
        
        for ($i = 1; $i <= 20; $i++) {
            $task = Task::create([
                'title' => 'Tarea de ejemplo ' . $i,
                'description' => 'Esta es una descripción de ejemplo para la tarea número ' . $i,
                'category_id' => Category::inRandomOrder()->first()->id,
                'is_completed' => rand(0, 1)
            ]);

            // Asignar etiquetas aleatorias
            $task->tags()->attach(
                $allTags->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}