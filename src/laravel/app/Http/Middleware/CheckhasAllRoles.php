<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Auth;

/**
 *  I have all of these roles!
 *
 * @package App\Http\Middleware
 */
class CheckhasAllRoles
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (!Auth::user()->hasAllRoles($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}

