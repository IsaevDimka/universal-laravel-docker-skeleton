<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class CheckApiToken
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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->token, config('api.tokens'))
            or in_array($request->getClientIP(), $this->ipWhiteList)
        ) {
            logger()->channel('mongodb')->debug(__METHOD__, [
                'collection' => 'API_v1_middleware_CheckApiToken_successful',
                'token' => $request->token,
            ]);
            return $next($request);
        }

        logger()->channel('mongodb')->error(__METHOD__, [
            'collection' => 'API_v1_middleware_CheckApiToken_error',
            'token' => $request->token,
        ]);
        throw new AuthenticationException();
    }
}
