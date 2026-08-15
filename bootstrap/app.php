<?php

use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (MassAssignmentException $ex) {
            return ApiResponse::errorResponse('MassAssignment', $ex->getMessage(), 500);
        });

        $exceptions->render(function (QueryException $ex) {
            return ApiResponse::errorResponse('Query', 'A database error occurred.', 500);
        });

        $exceptions->render(function (NotFoundHttpException $ex) {
            return ApiResponse::errorResponse('Results Not Found', 'Results Not Found', 404);
        });

        $exceptions->render(function (AccessDeniedHttpException $ex) {
            return ApiResponse::errorResponse('UnAuthorized', $ex->getMessage(), 403);
        });

        $exceptions->render(function (ValidationException $ex) {
            return ApiResponse::errorResponse('Validation', $ex->errors(), 422);
        });
        $exceptions->render(function (RelationNotFoundException $ex) {
            return ApiResponse::errorResponse('Relation of Model', $ex->getMessage(), 500);
        });
        // $exceptions->render(function (Error $ex) {
        //     return ApiResponse::errorResponse('Relation of Model', $ex->getMessage(), 500);
        // });

        // $exceptions->render(function (Throwable $ex) {
        //     return $ex->getMessage();
        // });

    })->create();
