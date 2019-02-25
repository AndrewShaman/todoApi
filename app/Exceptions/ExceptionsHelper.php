<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

trait ExceptionsHelper
{
    public function getModelJsonResponseException()
    {
        return $this->getResponse('Model not found.', Response::HTTP_NOT_FOUND);
    }

    public function getHttpJsonResponseException()
    {
        return $this->getResponse('Endpoint not found.', Response::HTTP_NOT_FOUND);
    }

    public function getBadRequestJsonResponseException($exception)
    {
        return $this->getResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
    }

    public function getValidationJsonResponseException($exception)
    {
        return $this->getResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
    }

    public function getForbiddenException()
    {
        return $this->getResponse('Access denied.', Response::HTTP_FORBIDDEN);
    }

    private function getResponse($message, $response)
    {
        return \response()->json([
            'data' => [
                'message' => $message,
                'status_code' => $response
            ]
        ], $response);
    }
}