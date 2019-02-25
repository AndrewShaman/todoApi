<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Project;
use App\Task;
use App\Http\Resources\TaskResource;

class TaskService
{
    /**
     * @param Project $project
     * @param int $id
     * @return mixed
     */
    public function getTask(Project $project, int $id)
    {
        return Task::where('project_id', $project->id)->findOrFail($id);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getTasksCollection(Project $project)
    {
        return TaskResource::collection(Task::where('project_id', $project->id)->with('project')->paginate(20));
    }

    /**
     * @param TaskRequest $request
     * @param Project $project
     * @return mixed
     */
    public function saveNewTask(TaskRequest $request, Project $project)
    {
        return Task::create($request->validated() + ['project_id' => $project->id]);
    }
}