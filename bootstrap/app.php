<?php




// use Illuminate\Foundation\Application;
// use App\Http\Middleware\EnforceJsonResponse;
// use Illuminate\Foundation\Configuration\Middleware;

use App\Traits\ApiResponseTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use function Termwind\render;
use Illuminate\Support\Facades\Log;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Application;
// use Illuminate\Support\Facades\Exceptions;
use Illuminate\Database\QueryException;
use App\Http\Middleware\EnforceJsonResponse;
use App\Http\Middleware\HasSetPinMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            EnforceJsonResponse::class,
            // HasSetPinMiddleware::class
        ]);
    })

    // $app = new \Illuminate\Foundation\Application(
    //     realpath(__DIR__.'/../')
    // );


    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
        //     if ($request->expectsJson() || Str::contains($request->path(), 'api') ) {
        //         Log::error($e);
        //         // dd($request->path());
        //     // dd($e);

        //     // return $this->sendError('An unknown error occurred, please try again later', Response::HTTP_INTERNAL_SERVER_ERROR, $e);


        //         // if ($e instanceof NotFoundHttpException) {
        //         //     return $this->apiResponse([
        //         //         'message' => $e->getMessage(),
        //         //         'success' => false,
        //         //         'exception' => $e,
        //         //         'error_code' => $e->getStatusCode()
        //         //     ], $e->getStatusCode());
        //         // }

        //         if ($e instanceof ValidationException) {
        //             $statusCode =  Response::HTTP_NOT_FOUND;

        //                 return $this->apiResponse([
        //                     'message' => $e->getMessage(),
        //                     'success' => false,
        //                     'exception' => $e,
        //                     'error_code' => $statusCode
        //                 ]);
        //             }

        //         if ($e instanceof ModelNotFoundException) {
        //             $statusCode =  Response::HTTP_NOT_FOUND;
        //             return $this->apiResponse(
        //                 [
        //                     'message' => 'Resource could not be found',
        //                     'success' => false,
        //                     'exception' => $e,
        //                     'error_code' => $statusCode
        //                 ]
        //             );
        //         }

        //         if ($e instanceof UniqueConstraintViolationException) {
        //             $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
        //             return $this->apiResponse(
        //                 [
        //                     'message' => 'Duplicate entry found',
        //                     'success' => false,
        //                     'exception' => $e,
        //                     'error_code' => $statusCode
        //                 ]
        //             );
        //         }

        //         if ($e instanceof QueryException) {
        //             $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
        //             return $this->apiResponse(
        //                 [
        //                     'message' => 'Could not execute query',
        //                     'success' => false,
        //                     'exception' => $e,
        //                     'error_code' => $statusCode
        //                 ],
        //                 $statusCode
        //             );
        //         }

        //         if ($e instanceof \Exception) {
        //             $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
        //             return $this->apiResponse(
        //                 [
        //                     'message' => 'An unknown error occurred, please try again later ',
        //                     'success' => false,
        //                     'exception' => $e,
        //                     'error_code' => $statusCode
        //                 ],
        //                 $statusCode
        //             );
        //         }

        //         if ($e instanceof \Error) {
        //             $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
        //             return $this->apiResponse(
        //                 [
        //                     'message' => 'An unknown error occurred, please try again later ',
        //                     'success' => false,
        //                     'exception' => $e,
        //                     'error_code' => $statusCode
        //                 ],
        //                 $statusCode
        //             );
        //         }
        //     }

        //     // return $request;
        //      return parent::render($request, $e);
        // });
    })->create();
