<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Passport\Token;
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
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(in_array($request->token, config('api.tokens'))
            or in_array($request->getClientIP(), $this->ipWhiteList)
        ){
            logger()->channel('mongodb')->debug(__METHOD__, [
                'collection'    => 'API_v1_middleware_CheckApiToken_successful',
                'token'         => $request->token,
            ]);
            return $next($request);
        }

        if(!empty(Token::whereId($request->token)->whereRevoked(false)->count())
        ){
            logger()->channel('mongodb')->debug(__METHOD__, [
                'collection'    => 'API_v1_middleware_CheckApiToken_successful',
                'token'         => $request->token,
            ]);
            return $next($request);
        }
        logger()->channel('mongodb')->error(__METHOD__, [
            'collection'    => 'API_v1_middleware_CheckApiToken_error',
            'token'         => $request->token,
        ]);
        throw new AuthenticationException;
    }
}
