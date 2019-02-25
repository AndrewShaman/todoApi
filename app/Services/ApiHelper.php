<?php

namespace App\Services;

use App\Project;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiHelper
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