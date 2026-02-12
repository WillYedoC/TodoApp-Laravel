<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
        'category_id'
    ];
    protected $casts = [
        'is_completed' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    // Scope global para filtrar por usuario autenticado
    protected static function booted()
    {
        static::addGlobalScope('user', function ($query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            }
        });
    }
}
