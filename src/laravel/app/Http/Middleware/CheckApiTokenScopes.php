<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Token;

class CheckApiTokenScopes
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\AuthenticationException|\Laravel\Passport\Exceptions\MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        $user_scopes = Token::whereId($request->token)->whereRevoked(false)->value('scopes');

        if (! $user_scopes) {
            throw new AuthenticationException;
        }

        foreach ($scopes as $scope) {
            if(in_array($scope, $user_scopes)){
                return $next($request);
            }
        }

        throw new AuthenticationException;
    }
}
