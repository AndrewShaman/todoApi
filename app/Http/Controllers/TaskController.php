<?php

namespace App\Http\Controllers;

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
     * @return TaskResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Project $project)
    {
        $validated = Validator::make($request->all(), [
            'description' => 'required|string|max:255'
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

        $task = Task::create($request->all() + ['project_id' => $project->id]);

        return new TaskResource($task);
    }

    /**
     * @param Project $project
     * @param Task $task
     * @param Request $request
     * @return TaskResource|\Illuminate\Http\JsonResponse
     */
    public function update(Project $project, Task $task, Request $request)
    {
        if ($request->user()->id != $project->id) {
            throw new AccessDeniedHttpException();
        }

        $validated = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

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
        if ($request->user()->id != $project->id) {
            throw new AccessDeniedHttpException();
        }

        $task->delete();

        throw new NoContentHttpException();
    }
}
