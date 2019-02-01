<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\apiAs;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use apiAs;
    use DatabaseMigrations;

    /** @test */
    function guests_cant_see_projects()
    {
        $this->json('GET', '/api/projects')->assertJson(['status' => 'Authorization Token not found']);
    }

    /** @test */
    function user_can_see_his_project()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => $user->id]);

        $this->apiAs($user, 'GET', "api/projects/$project->id")
            ->assertJson([
                'id' => $project->id,
                'user_id' => $user->id,
                'title' => $project->title,
                'description' => $project->description,
            ])
            ->assertStatus(200);
    }

    /** @test */
    function user_can_see_only_his_projects()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => 999]);

        $this->apiAs($user,'GET',"/api/projects/$project->id")->assertStatus(403);
    }

    /** @test */
    function user_can_create_a_project()
    {
        $user = factory('App\User')->create();

        $this->apiAs($user, 'POST', '/api/projects', [
            'user_id' => $user->id,
            'title' => 'Title',
            'description' => 'Description'
        ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'Title',
            'description' => 'Description'
        ]);
    }

    /** @test */
    function user_can_update_project()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => $user->id]);

        $this->apiAs($user,'PATCH', "/api/projects/$project->id", [
            'title' => 'New Title',
            'description' => 'New Description',
        ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'New Title',
            'description' => 'New Description'
        ]);
    }

    /** @test */
    function user_can_delete_project()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create(['user_id' => $user->id]);

        $this->apiAs($user, 'DELETE', "/api/projects/$project->id", $project->toArray())
            ->assertStatus(204);
        $this->assertDatabaseMissing('projects', $project->toArray());
    }

    /** @test */
    function user_can_delete_only_his_projects()
    {
        $user = factory('App\User')->create(['id' => 0]);
        $project = factory('App\Project')->create(['user_id' => 1]);

        $this->apiAs($user, 'DELETE', "/api/projects/$project->id", $project->toArray())->assertStatus(403);
    }
}
