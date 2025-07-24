<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/tasks', [
                 'name' => 'Test Task',
                 'project_id' => 1
             ])
             ->assertRedirect('/');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
            'user_id' => $user->id
        ]);
    }
}
