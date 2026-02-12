<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Tag extends Model
{
    protected $fillable = [
        'user_id',
        'name'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
    // Scope global
    protected static function booted()
    {
        static::addGlobalScope('user', function ($query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            }
        });
    }
}
