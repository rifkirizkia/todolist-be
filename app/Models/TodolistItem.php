<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodolistItem extends Model
{
    use HasFactory;

    protected $fillable = ['item', 'completed'];

    public function todolist(): BelongsTo
    {
        return $this->belongsTo(Todolist::class);
    }
}
