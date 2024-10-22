<?php

namespace Tests\Feature;

use App\Models\Todolist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodolistTest extends TestCase
{
    use RefreshDatabase;

    private Todolist $todolist;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->todolist = Todolist::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);
    }

    public function test_fetch_all_todolist(): void
    {
        $response = $this->getJson(route('todolist.index'))
            ->assertOk()
            ->json();

        $this->assertEquals($this->todolist->name, $response[0]['name']);
    }

    public function test_fetch_single_todolist(): void
    {
        $response = $this->getJson(route('todolist.show', $this->todolist->id))
            ->assertOk()
            ->json();

        $this->assertEquals($this->todolist->name, $response['name']);
    }

    public function test_create_todolist(): void
    {
        $response = $this->postJson(route('todolist.store'), ['name' => fake()->sentence])
            ->assertCreated()
            ->json();

        $this->assertDatabaseHas('todolists', ['name' => $response['name']]);
    }

    public function test_validation_name_is_required_when_creating_todolist(): void
    {
        $this->withExceptionHandling();

        $this->postJson(route('todolist.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todolist(): void
    {
        $this->deleteJson(route('todolist.destroy', $this->todolist->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todolists', ['name' => $this->todolist->id]);
    }

    public function test_update_todolist(): void
    {
        $oldName = $this->todolist->name;
        $response = $this->putJson(route('todolist.update', $this->todolist->id), ['name' => fake()->sentence()])
            ->assertOk()
            ->json();

        $this->assertDatabaseHas('todolists', ['name' => $response['name']]);
        $this->assertDatabaseMissing('todolists', ['name' => $oldName]);
    }
}
