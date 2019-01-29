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
    function project_can_be_updated()
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
    function project_can_be_deleted()
    {
        $user = factory('App\User')->create();
        $project = factory('App\Project')->create();

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
