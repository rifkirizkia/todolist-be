<?php

namespace Tests\Feature;

use App\Models\Todolist;
use App\Models\TodolistItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodolistItemTest extends TestCase
{
    use RefreshDatabase;

    protected TodolistItem $todolistItem;
    protected Todolist $todolist;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->todolistItem = TodolistItem::factory()->create([
            'todolist_id' => Todolist::factory()->create(['user_id' => $user->id])
        ])->load('todolist');

        $this->todolist = $this->todolistItem->todolist;
    }

    public function test_fetch_all_task_from_todolists(): void
    {
        $response = $this->getJson(route('items.index', $this->todolistItem->todolist->id))
            ->assertOk()
            ->json();

        $this->assertEquals(1, count($response));
    }

    public function test_create_task_in_todolist(): void
    {
        $response = $this->postJson(route('items.store', $this->todolist->id), ['item' => fake()->sentence])
            ->assertCreated()
            ->json();

        $this->assertDatabaseHas('todolist_items', ['item' => $response['item']]);
    }

    public function test_delete_task_in_todolist(): void
    {
        $this->deleteJson(route('items.destroy', [$this->todolistItem->todolist->id, $this->todolistItem->id]))
            ->assertNoContent();

        $this->assertDatabaseMissing('todolist_items', ['item', $this->todolistItem->name]);
    }

    public function test_update_task_in_todolist(): void
    {
        $oldItem = $this->todolistItem->name;
        $response = $this->patchJson(route('items.update', [$this->todolistItem->todolist->id, $this->todolistItem->id]), ['item' => fake()->sentence])
            ->assertOk()
            ->json();

        $this->assertDatabaseHas('todolist_items', ['item' => $response['item']]);
        $this->assertDatabaseMissing('todolist_items', ['item' => $oldItem]);
    }

    public function test_complete_status_task_in_todolist(): void
    {
        $response = $this->patchJson(route('items.update.status', [$this->todolistItem->todolist->id, $this->todolistItem->id]), ['completed' => true])
            ->assertOk()
            ->json();

        $this->assertEquals(true, $response['completed']);
    }
}
