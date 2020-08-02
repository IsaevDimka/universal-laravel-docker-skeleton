<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\ApiController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $roles = $user->getRoleNames()->toArray();
        $permissions = $user->getPermissionNames()->toArray();

        $user_data = array_merge($user->toArray(), compact('roles', 'permissions'));
        return response()->json($user_data);
    }
}
