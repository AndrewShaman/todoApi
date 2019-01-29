<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\apiAs;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksTest extends TestCase
{
    use apiAs;
    use DatabaseMigrations;

    /** @test */
    function user_can_create_task()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => $user->id]);

        $this->apiAs($user, 'POST', "api/projects/$project->id/tasks", [
            'description' => 'Test Description'
        ]);

        $this->assertDatabaseHas('tasks', [
            'description' => 'Test Description'
        ]);
    }

    /** @test */
    function user_can_update_task()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create();

        $this->apiAs($user, 'PATCH', "api/projects/$task->project_id/tasks/$task->id", [
            'description' => 'New Description'
        ]);

        $this->assertDatabaseHas('tasks', [
            'description' => 'New Description'
        ]);
    }

    // complete == delete
    /** @test */
    function user_can_complete_tasks()
    {
        $user = factory('App\User')->create();
        $task = factory('App\Task')->create();

        $this->apiAs($user, 'DELETE', "api/projects/$task->project_id/tasks/$task->id", $task->toArray())
            ->assertStatus(204);

        $this->assertDatabaseMissing('tasks', $task->toArray());
    }

    /** @test */
    function user_can_complete_only_his_tasks()
    {
        $user = factory('App\User')->create();
        factory('App\Project')->create(['user_id' => 0]);
        $task = factory('App\Task')->create();

        $this->apiAs($user, 'DELETE', "api/projects/$task->project_id/tasks/$task->id", $task->toArray())
            ->assertStatus(403);
    }
}
