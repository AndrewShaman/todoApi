<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ProjectBadRequestHttpException;
use App\Http\Resources\ProjectResource;
use App\Project;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CustomExceptions\NoContentHttpException;

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
     * @return ProjectResource
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        if($validated->fails()) {
            throw new ProjectBadRequestHttpException();
        }

        $project = Project::create($request->all() + ['user_id' => auth()->id()]);

        return new ProjectResource($project);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return ProjectResource
     */
    public function show(Request $request, Project $project)
    {
        if ($request->user()->id != $project->user_id) {
            throw new AccessDeniedHttpException();
        }

        return new ProjectResource($project);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return ProjectResource
     */
    public function update(Request $request, Project $project)
    {
        if ($request->user()->id != $project->user_id) {
            throw new AccessDeniedHttpException();
        }

        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        if($validated->fails()) {
            throw new ProjectBadRequestHttpException();
        }

        $project->update($request->only(['title', 'description']));
        return new ProjectResource($project);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @throws \Exception
     */
    public function destroy(Request $request, Project $project)
    {
        if ($request->user()->id != $project->id) {
            throw new AccessDeniedHttpException();
        }

        $project->delete();

        throw new NoContentHttpException();
    }
}
