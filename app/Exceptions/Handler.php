<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => $e->errors()
                    ],
                    400
                );
            }
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null
                    ],
                    404
                );
            }
        });
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null,
                    ],
                    403
                );
            }
        });
        $this->renderable(function (UnauthorizedException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null,
                    ],
                    403
                );
            }
        });
        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null
                    ],
                    403
                );
            }
        });
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null
                    ],
                    401
                );
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is("api/v1/*")) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => $e->getTraceAsString(),
                    ],
                    405
                );
            }
        });
        $this->renderable(function (Exception $e, Request $request) {
            if ($request->is("api/v1/*")) {
                Log::error($e->getMessage(), [
                    'url'     => $request->fullUrl(),
                    'params'  => $request->all(),
                    'user_id' => auth()->id() ?? null,
                    'trace'   => $e->getTrace()
                ]);

                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null,
                    ],
                    500
                );
            }
        });
        $this->renderable(function (\Error $e, Request $request) {
            if ($request->is("api/v1/*")) {
                Log::error($e->getMessage(), [
                    'url'     => $request->fullUrl(),
                    'params'  => $request->all(),
                    'user_id' => auth()->id() ?? null,
                    'trace'   => $e->getTrace()
                ]);

                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data'    => null,
                    ],
                    500
                );
            }
        });
    }
}
