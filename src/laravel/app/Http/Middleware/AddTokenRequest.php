<?php

namespace App\Http\Middleware;

use Closure;

class AddTokenRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken() ?? $request->header('X-Token') ?? $request->token ?? $request->api_key ?? $request->access_token ?? null;
        $request->merge(compact('token'));

        return $next($request);
    }
}
