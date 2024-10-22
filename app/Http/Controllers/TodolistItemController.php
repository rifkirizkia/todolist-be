<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodolistItemRequest;
use App\Models\Todolist;
use App\Models\TodolistItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TodolistItemController extends Controller
{
    public function index(Todolist $todolist): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        $todolistsItem = $todolist->items()->get();

        return response()->json($todolistsItem, Response::HTTP_OK);
    }

    public function store(TodolistItemRequest $request, Todolist $todolist): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        $data = $request->validated();

        $items = $todolist->items()->create($data);

        return response()->json($items, Response::HTTP_CREATED);
    }

    public function destroy(Todolist $todolist, TodolistItem $todolistItem): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        if ($todolist->id !== $todolistItem->todolist->id) {
            return response()->json([], Response::HTTP_FORBIDDEN);
        }

        $todolistItem->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function update(TodolistItemRequest $request, Todolist $todolist, TodolistItem $todolistItem): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        $data = $request->validated();

        $todolistItem->update($data);

        return response()->json($todolistItem, 200);
    }

    public function status(Request $request, Todolist $todolist, TodolistItem $todolistItem): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        $data = $request->validate([
            'completed' => 'required|boolean'
        ]);

        $todolistItem->update($data);

        return response()->json($todolistItem, 200);
    }
}
