<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomExceptions\ProjectBadRequestHttpException;
use App\Http\Resources\ProjectResource;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Auth;
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
        $this->isProjectValid($request);
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
        $this->isOwner($request, $project);

        return new ProjectResource($project);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return ProjectResource
     */
    public function update(Request $request, Project $project)
    {
        $this->isOwner($request, $project);
        $this->isProjectValid($request);
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
//        User::getUserAndProjectById(auth()->user()->id);
//        $project = Project::findOrFail($id);
        $this->isOwner($request, $project);
        $project->delete();

        throw new NoContentHttpException();
    }

    /**
     * @param Request $request
     * @param Project $project
     */
    protected function isOwner(Request $request, Project $project)
    {
        if ($request->user()->id != $project->user_id) {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @param Request $request
     */
    protected function isProjectValid(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        if ($validated->fails()) {
            throw new ProjectBadRequestHttpException();
        }
    }
}
