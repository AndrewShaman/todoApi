<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
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
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
