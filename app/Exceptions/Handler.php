<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    use ExceptionsHelper;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->getModelJsonResponseException();
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return $this->getHttpJsonResponseException();
        } elseif ($exception instanceof ValidationException){
            return $this->getValidationJsonResponseException($exception);
        } elseif ($exception instanceof AccessDeniedHttpException) {
            return $this->getForbiddenException();
        }
        return $this->getBadRequestJsonResponseException($exception);

//        return parent::render($request, $exception);
    }
}
