<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todolist extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function items(): HasMany
    {
        return $this->hasMany(TodolistItem::class, 'todolist_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
