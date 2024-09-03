<?php

namespace App\Exceptions;

use Exception;
use Throwable;
// use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;


    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation'
    ];

    // public function register(): void{
    //     $this->reportable(function (Throwable $e){

    //     });
    // }

    public function render($request, Throwable $e)
    {
        // dd($request->path());
        if ($request->expectsJson() || Str::contains($request->path(), 'api')) {
            Log::error($e);
        dd($request->path());


            if ($e instanceof NotFoundHttpException) {
                return $this->apiResponse([
                    'message' => $e->getMessage(),
                    'success' => false,
                    'exception' => $e,
                    'error_code' => $e->getStatusCode()
                ], $e->getStatusCode());
            }

            if ($e instanceof ModelNotFoundException) {
                $statusCode =  Response::HTTP_NOT_FOUND;
                return $this->apiResponse(
                    [
                        'message' => 'Resource could not be found',
                        'success' => false,
                        'exception' => $e,
                        'error_code' => $statusCode
                    ]
                );
            }

            if ($e instanceof UniqueConstraintViolationException) {
                $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
                return $this->apiResponse(
                    [
                        'message' => 'Duplicate entry found',
                        'success' => false,
                        'exception' => $e,
                        'error_code' => $statusCode
                    ]
                );
            }

            if ($e instanceof QueryException) {
                $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
                return $this->apiResponse(
                    [
                        'message' => 'Could not execute query',
                        'success' => false,
                        'exception' => $e,
                        'error_code' => $statusCode
                    ],
                    $statusCode
                );
            }

            if ($e instanceof \Exception) {
                $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
                return $this->apiResponse(
                    [
                        'message' => 'An unknown error occurred, please try again later ',
                        'success' => false,
                        'exception' => $e,
                        'error_code' => $statusCode
                    ],
                    $statusCode
                );
            }

            if ($e instanceof \Error) {
                $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
                return $this->apiResponse(
                    [
                        'message' => 'An unknown error occurred, please try again later ',
                        'success' => false,
                        'exception' => $e,
                        'error_code' => $statusCode
                    ],
                    $statusCode
                );
            }
        }

        return parent::render($request, $e);
    }
}
