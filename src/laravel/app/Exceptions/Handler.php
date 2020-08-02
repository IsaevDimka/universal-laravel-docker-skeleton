<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
            if(in_array('api', Route::getCurrentRoute()->middleware()) || $request->wantsJson()){
                return api()->response(
                    503,
                    !empty($exception->getMessage()) ? $exception->getMessage() : 'Service Unavailable'
                );
            }
            throw new HttpException(503, $exception->getMessage());
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
            if(in_array('api', Route::getCurrentRoute()->middleware()) || $request->wantsJson()){
                if($request->expectsJson())
                {
                    return api()->notFound('Entry for '.str_replace(self::MODELS_NAMESPACE, '', $exception->getModel()).' by ids: '.implode(',', $exception->getIds()).' not found');
                }
            }
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
            ? api()->response(401, $exception->getMessage())
            : redirect()->guest(url('/login'));
    }
}
