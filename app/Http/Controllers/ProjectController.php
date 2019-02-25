<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    protected $project;

    /**
     * ProjectController constructor.
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->project = $projectService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return $this->project->getProjectsCollection();
    }

    /**
     * @param ProjectRequest $request
     * @return mixed
     */
    public function store(ProjectRequest $request)
    {
        return $this->project->saveNewProject($request);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->project->getProject($id);
    }

    /**
     * @param ProjectRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(ProjectRequest $request, int $id)
    {
        return $this->project->getProject($id)->update($request->validated());
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $this->project->getProject($id)->delete();

        return response()->json(null,Response::HTTP_NO_CONTENT);
    }
}
