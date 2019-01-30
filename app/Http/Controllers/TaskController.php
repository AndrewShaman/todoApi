<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\TaskBadRequestHttpException;
use App\Project;
use App\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Exceptions\CustomExceptions\NoContentHttpException;

class TaskController extends Controller
{
    /**
     * @param Project $project
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Project $project)
    {
        return TaskResource::collection(Task::where('project_id', $project->id)->get());
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return TaskResource
     */
    public function store(Request $request, Project $project)
    {
        $this->isTaskValid($request);
        $task = Task::create($request->all() + ['project_id' => $project->id]);

        return new TaskResource($task);
    }

    /**
     * @param Project $project
     * @param Task $task
     * @param Request $request
     * @return TaskResource
     */
    public function update(Project $project, Task $task, Request $request)
    {
        $this->isOwner($project, $request);
        $this->isTaskValid($request);
        $task->update($request->only('description'));

        return new TaskResource($task);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @param Task $task
     * @throws \Exception
     */
    public function destroy(Request $request, Project $project, Task $task)
    {
        $this->isOwner($project, $request);
        $task->delete();

        throw new NoContentHttpException();
    }

    /**
     * @param Request $request
     */
    protected function isTaskValid(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'description' => 'required|string|max:255'
        ]);

        if ($validated->fails()) {
            throw new TaskBadRequestHttpException();
        }
    }

    /**
     * @param Project $project
     * @param Request $request
     */
    protected function isOwner(Project $project, Request $request)
    {
        if ($request->user()->id != $project->id) {
            throw new AccessDeniedHttpException();
        }
    }
}
