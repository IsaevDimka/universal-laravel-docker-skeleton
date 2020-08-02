<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class CheckApiConfigToken
{
    protected $ipWhiteList;

    public function __construct()
    {
        $this->ipWhiteList = config('api.ipWhiteList');
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(
            in_array($request->token, config('api.tokens'))
            or in_array($request->getClientIP(), $this->ipWhiteList)
        ){
            logger()->channel('mongodb')->debug('API_v1_middleware_token_config', [
                'collection'    => 'API_v1_middleware_token_config_request',
                'token'         => $request->token,
                'status'        => true
            ]);
            return $next($request);
        }
        logger()->channel('mongodb')->error('API_v1_middleware_token_config', [
            'collection'    => 'API_v1_middleware_token_config_error',
            'token'         => $request->token,
            'status'        => false
        ]);
        throw new AuthenticationException;
    }
}
