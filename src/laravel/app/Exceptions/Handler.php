<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        'password',
        'password_confirmation',
    ];

    /**
     * @var
     */
    const MODELS_NAMESPACE = 'App\Models\\';

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->environment('production', 'develop') && app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof MaintenanceModeException){
            if($request->expectsJson()){
                return api()->response(
                    503,
                    !empty($exception->getMessage()) ? $exception->getMessage() : 'Service Unavailable'
                );
            }
            throw new HttpException(Response::HTTP_SERVICE_UNAVAILABLE, $exception->getMessage());
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException && $request->expectsJson()){
            return api()->notFound('Entry for '.str_replace(self::MODELS_NAMESPACE, '', $exception->getModel()).' by ids: '.implode(',', $exception->getIds()).' not found');
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException && $request->expectsJson()) {
            return api()->notFound();
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException && $request->expectsJson()) {
            return api()->forbidden($exception->getMessage());
        }

        if ($exception instanceof ValidationException && $request->expectsJson()) {
            return api()->validation($exception->getMessage(), $exception->validator->errors()->toArray());
        }

        if ($exception instanceof MethodNotAllowedHttpException && $request->expectsJson()) {
            return api()->response(Response::HTTP_METHOD_NOT_ALLOWED, $exception->getMessage());
        }

        if ($exception instanceof \Illuminate\Contracts\Encryption\DecryptException && $request->expectsJson()) {
            return api()->forbidden('Forbidden (cors).');
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException && $request->expectsJson()) {
            return api()->forbidden((string) $exception->getMessage());
        }

        if($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException && $request->expectsJson())
        {
            return api()->response(
                Response::HTTP_TOO_MANY_REQUESTS,
                (string) $exception->getMessage()
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? api()->response(Response::HTTP_UNAUTHORIZED, $exception->getMessage())
            : redirect()->guest(url('/login'));
    }
}
