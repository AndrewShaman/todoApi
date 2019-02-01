<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

trait ExceptionsHelper
{
    public function getModelJsonResponseException()
    {
        return response()->json([
            'data' => [
                'message' => 'Model not found',
                'status_code' => Response::HTTP_NOT_FOUND
            ]
        ], Response::HTTP_NOT_FOUND);
    }

    public function getHttpJsonResponseException()
    {
        return response()->json([
            'data' => [
                'message' => 'Endpoint not found',
                'status_code' => Response::HTTP_NOT_FOUND
            ]
        ], Response::HTTP_NOT_FOUND);
    }

    public function getBadRequestJsonResponseException($exception)
    {
        return response()->json([
            'data' => [
                'message' => $exception->getMessage(),
                'status_code' => Response::HTTP_BAD_REQUEST
            ]
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getValidationJsonResponseException($exception)
    {
        return response()->json([
            'data' => [
                'message' => $exception->errors(),
                'status_code' => Response::HTTP_BAD_REQUEST
            ]
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getForbiddenException()
    {
        return response()->json([
            'data' => [
                'message' => 'Access denied.',
                'status_code' => Response::HTTP_FORBIDDEN
            ]
        ], Response::HTTP_FORBIDDEN);
    }
}