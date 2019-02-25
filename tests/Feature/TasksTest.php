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
    function user_can_see_his_task()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        $task = factory('App\Task')->create(['project_id' => $project->id]);

        $this->apiAs($user, 'GET', "api/projects/$project->id/tasks/$task->id")
            ->assertJson([
                'id' => $task->id,
                'project_id' => $project->id,
                'description' => $task->description,
            ])
            ->assertStatus(200);
    }

    /** @test */
    function user_can_update_task()
    {
        $user = factory('App\User')->create(['id' => 1]);
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        $task = factory('App\Task')->create(['project_id' => $project->id]);

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
        $user = factory('App\User')->create(['id' => 1]);
        $project = factory('App\Project')->create(['user_id' => $user->id]);
        $task = factory('App\Task')->create(['project_id' => $project->id]);

        $this->apiAs($user, 'DELETE', "api/projects/$task->project_id/tasks/$task->id", $task->toArray())
            ->assertStatus(204);

        $this->assertDatabaseMissing('tasks', $task->toArray());
    }

    /** @test */
    function user_can_see_tasks_only_of_his_projects()
    {
        $user = factory('App\User')->create();
        factory('App\Project')->create(['user_id' => 0]);
        $task = factory('App\Task')->create();

        $this->apiAs($user, 'GET', "api/projects/$task->project_id/tasks/$task->id")->assertStatus(403);
    }

    /** @test */
    function user_can_update_only_his_tasks()
    {
        $user = factory('App\User')->create();
        factory('App\Project')->create(['user_id' => 0]);
        $task = factory('App\Task')->create();

        $this->apiAs($user, 'PATCH', "api/projects/$task->project_id/tasks/$task->id", $task->toArray())
            ->assertStatus(403);
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
