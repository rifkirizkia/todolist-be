<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodolistRequest;
use App\Models\Todolist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodolistController extends Controller
{
    public function index(): JsonResponse
    {
        $todolists = Todolist::where('user_id', Auth::user()->id)->get();
        return response()->json($todolists);
    }

    public function show(Todolist $todolist): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        return response()->json($todolist);
    }

    public function store(TodolistRequest $request): JsonResponse
    {
        $data = $request->validated();

        $todolist = $request->user()->todolist()->create($data);

        return response()->json($todolist, 201);
    }

    public function destroy(Todolist $todolist): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        $todolist->delete();

        return response()->json([], 204);
    }

    public function update(TodolistRequest $request, Todolist $todolist): JsonResponse
    {
        if (Auth::user()->id !== $todolist->user_id) {
            return response()->json([], 403);
        }

        $data = $request->validated();

        $todolist->update($data);

        return response()->json($todolist, 200);
    }
}
