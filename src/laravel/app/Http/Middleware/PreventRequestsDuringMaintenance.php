<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Route;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        'artisan-remote/*'
    ];

    protected $exceptRoutes = [
        'debug.index',
        'api.v1.status'
    ];

    protected $excludedIPs = [];

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            $response = $next($request);

            if (in_array($request->ip(), $this->excludedIPs)) {
                return $response;
            }

            $route = $request->route();

            if ($route instanceof Route) {
                if (in_array($route->getName(), $this->exceptRoutes)) {
                    return $response;
                }
            }

            if ($this->shouldPassThrough($request))
            {
                return $response;
            }

            parent::handle($request, $next);
        }

        return $next($request);
    }

}
