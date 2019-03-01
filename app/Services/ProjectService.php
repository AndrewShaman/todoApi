<?php

namespace App\Services;

use App\Project;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;

class ProjectService
{
    /**
     * @param $id
     * @return mixed
     */
    public function getProject($id)
    {
        return Project::where('user_id', auth()->id())->findOrFail($id);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProjectsCollection()
    {
        return ProjectResource::collection(Project::where('user_id', auth()->id())->with('user')->paginate(20));
    }

    /**
     * @param ProjectRequest $request
     * @return mixed
     */
    public function saveNewProject(ProjectRequest $request)
    {
        return Project::create($request->validated() + ['user_id' => auth()->id()]);
    }
}