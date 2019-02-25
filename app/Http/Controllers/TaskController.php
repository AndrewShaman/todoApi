<?php

namespace App\Http\Controllers;

use App\Services\ApiHelper;
use App\Http\Requests\TaskRequest;
use App\Project;
use App\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * @param Project $project
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Project $project)
    {
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);
        return TaskResource::collection(Task::where('project_id', $project->id)->with('project')->paginate(20));
    }

    /**
     * @param TaskRequest $request
     * @param Project $project
     * @return mixed
     */
    public function store(TaskRequest $request, Project $project)
    {
        $task = Task::create($request->validated() + ['project_id' => $project->id]);

        return $task;
    }

    /**
     * @param Project $project
     * @param int $id
     * @return mixed
     */
    public function show(Project $project, int $id)
    {
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);
        $task = Task::findOrFail($id)->where('project_id', $project->id)->find($id);

        return $task;
    }

    /**
     * @param Project $project
     * @param Task $task
     * @param TaskRequest $request
     * @return Task
     */
    public function update(Project $project, Task $task, TaskRequest $request)
    {
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);
        $task->update($request->validated());

        return $task;
    }

    /**
     * @param Project $project
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Project $project, Task $task)
    {
        $user = app()->make(ApiHelper::class);
        $user->isOwner($project);
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}