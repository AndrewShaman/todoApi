<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * @param Request $request
     * @return ProjectResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

        $project = Project::create($request->all() + ['user_id' => auth()->id()]);

        return new ProjectResource($project);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return ProjectResource|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Project $project)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        return new ProjectResource($project);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return ProjectResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['error' => 'You can edit only your projects.'], 403);
        }

        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

        $project->update($request->only(['title', 'description']));
        return new ProjectResource($project);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(null, 204);
    }
}
