<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodolistController;
use App\Http\Controllers\TodolistItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list', TodolistController::class)
        ->parameters(['todo-list' => 'todolist'])
        ->names('todolist');

    Route::get('todo-list/{todolist}/items', [TodolistItemController::class, 'index'])->name('items.index');
    Route::post('todo-list/{todolist}/items', [TodolistItemController::class, 'store'])->name('items.store');
    Route::delete('todo-list/{todolist}/items/{todolistItem}', [TodolistItemController::class, 'destroy'])->name('items.destroy');
    Route::patch('todo-list/{todolist}/items/{todolistItem}/status', [TodolistItemController::class, 'status'])->name('items.update.status');
    Route::patch('todo-list/{todolist}/items/{todolistItem}', [TodolistItemController::class, 'update'])->name('items.update');
});

Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
