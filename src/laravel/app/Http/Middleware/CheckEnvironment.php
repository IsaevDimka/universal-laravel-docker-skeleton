<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class CheckEnvironment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $env)
    {
        $env = is_array($env)
            ? $env
            : explode('|', $env);

        if (! app()->environment($env)) {
            return api()->forbidden();
        }

        return $next($request);
    }
}
