<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'María García',
            'email' => 'maria@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Crear categorías para Usuario 1
        $categoriesUser1 = [
            'Trabajo',
            'Personal',
            'Estudios',
        ];

        $user1Categories = [];
        foreach ($categoriesUser1 as $categoryName) {
            $user1Categories[] = $user1->categories()->create(['name' => $categoryName]);
        }

        // Crear etiquetas para Usuario 1
        $tagsUser1 = [
            'Urgente',
            'Importante',
            'Corto plazo',
        ];

        $user1Tags = [];
        foreach ($tagsUser1 as $tagName) {
            $user1Tags[] = $user1->tags()->create(['name' => $tagName]);
        }

        // Crear tareas para Usuario 1
        for ($i = 1; $i <= 5; $i++) {
            $task = $user1->tasks()->create([
                'title' => "Tarea de Juan #{$i}",
                'description' => "Esta es la tarea número {$i} de Juan Pérez",
                'category_id' => $user1Categories[array_rand($user1Categories)]->id,
                'is_completed' => rand(0, 1) === 1,
            ]);

            $randomTags = collect($user1Tags)
                ->random(rand(1, min(2, count($user1Tags))))
                ->pluck('id')
                ->toArray();
            
            $task->tags()->attach($randomTags);
        }

        // Crear categorías para Usuario 2
        $categoriesUser2 = [
            'Hogar',
            'Salud',
            'Fitness',
            'Proyectos',
        ];

        $user2Categories = [];
        foreach ($categoriesUser2 as $categoryName) {
            $user2Categories[] = $user2->categories()->create(['name' => $categoryName]);
        }

        // Crear etiquetas para Usuario 2
        $tagsUser2 = [
            'Largo plazo',
            'Planificación',
            'Revisión',
            'Prioridad alta',
        ];

        $user2Tags = [];
        foreach ($tagsUser2 as $tagName) {
            $user2Tags[] = $user2->tags()->create(['name' => $tagName]);
        }

        // Crear tareas para Usuario 2
        for ($i = 1; $i <= 15; $i++) {
            $task = $user2->tasks()->create([
                'title' => "Tarea de María #{$i}",
                'description' => "Esta es la tarea número {$i} de María García",
                'category_id' => $user2Categories[array_rand($user2Categories)]->id,
                'is_completed' => rand(0, 1) === 1,
            ]);

            $randomTags = collect($user2Tags)
                ->random(rand(1, min(3, count($user2Tags))))
                ->pluck('id')
                ->toArray();
            
            $task->tags()->attach($randomTags);
        }

        // Crear categorías para Usuario 3
        $categoriesUser3 = [
            'Desarrollo',
            'Reuniones',
            'Aprendizaje',
        ];

        $user3Categories = [];
        foreach ($categoriesUser3 as $categoryName) {
            $user3Categories[] = $user3->categories()->create(['name' => $categoryName]);
        }

        // Crear etiquetas para Usuario 3
        $tagsUser3 = [
            'Backend',
            'Frontend',
            'Documentación',
        ];

        $user3Tags = [];
        foreach ($tagsUser3 as $tagName) {
            $user3Tags[] = $user3->tags()->create(['name' => $tagName]);
        }

        // Crear tareas para Usuario 3
        for ($i = 1; $i <= 8; $i++) {
            $task = $user3->tasks()->create([
                'title' => "Tarea de Carlos #{$i}",
                'description' => "Esta es la tarea número {$i} de Carlos López",
                'category_id' => $user3Categories[array_rand($user3Categories)]->id,
                'is_completed' => rand(0, 1) === 1,
            ]);

            // Asignar etiquetas aleatorias (1 a 2 tags)
            $randomTags = collect($user3Tags)
                ->random(rand(1, min(2, count($user3Tags))))
                ->pluck('id')
                ->toArray();
            
            $task->tags()->attach($randomTags);
        }
    }
}