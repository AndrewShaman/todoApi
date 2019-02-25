<?php

namespace App\Services;

use App\Http\Requests\ProjectRequest;
use App\Project;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Http\Resources\ProjectResource;

class ApiHelperService
{
    /**
     * @param Project $project
     */
    public function isOwner(Project $project)
    {
        if (request()->user()->id != $project->user_id) {
            throw new AccessDeniedHttpException;
        }
    }
}