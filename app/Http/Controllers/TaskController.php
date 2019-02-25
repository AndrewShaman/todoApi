<?php

namespace App\Http\Controllers;

use App\Services\ApiHelperService;
use App\Http\Requests\TaskRequest;
use App\Project;
use App\Services\TaskService;
use App\Task;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * @var ApiHelperService
     */
    protected $user;

    /**
     * @var TaskService
     */
    protected $task;

    /**
     * TaskController constructor.
     * @param ApiHelperService $service
     * @param TaskService $taskService
     */
    public function __construct(ApiHelperService $service, TaskService $taskService)
    {
        $this->user = $service;
        $this->task = $taskService;
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Project $project)
    {
        $this->user->isOwner($project);
        return $this->task->getTasksCollection($project);
    }

    /**
     * @param TaskRequest $request
     * @param Project $project
     * @return mixed
     */
    public function store(TaskRequest $request, Project $project)
    {
        return $this->task->saveNewTask($request, $project);
    }

    /**
     * @param Project $project
     * @param int $id
     * @return mixed
     */
    public function show(Project $project, int $id)
    {
        $this->user->isOwner($project);
        return $this->task->getTask($project, $id);
    }

    /**
     * @param Project $project
     * @param Task $task
     * @param TaskRequest $request
     * @return Task
     */
    public function update(Project $project, Task $task, TaskRequest $request)
    {
        $this->user->isOwner($project);
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
        $this->user->isOwner($project);
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}