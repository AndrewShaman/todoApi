<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Project;
use App\Services\ApiHelper;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ProjectResource::collection(Project::where('user_id', auth()->id())->with('user')->paginate(20));
    }

    /**
     * @param ProjectRequest $request
     * @return mixed
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated() + ['user_id' => auth()->id()]);

        return $project;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $project = Project::findOrFail($id);
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);

        return $project;
    }

    /**
     * @param ProjectRequest $request
     * @param Project $project
     * @return Project
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);
        $project->update($request->validated());

        return $project;
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $project = Project::findOrFail($id);
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);
        $project->delete();

        return response()->json(null,Response::HTTP_NO_CONTENT);
    }
}
