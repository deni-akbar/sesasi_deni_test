<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenExpiredException) {
            return response()->json(['error' => 'Token expired'], 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        if ($exception instanceof JWTException) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'message' => 'Token not provided or unauthorized.'
        ], 401);
    }
}
