<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\ApiController;
use App\Jobs\UserLoginActivityStoreJob;
use App\Jobs\UserUpdateLastVisitAt;
use App\Models\User;
use App\Http\Resources\UserResource;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * Get authenticated user.
     */
    public function current(Request $request)
    {
        $user_id = $request->user()->id;

        dispatch(new UserUpdateLastVisitAt($user_id));
        dispatch(new UserLoginActivityStoreJob($user_id, $request->getClientIp(), $request->userAgent()));

        $expiration = Auth::guard()->getPayload()->get('exp');
        $expires_in = $expiration - time();

        return api()->ok(null, UserResource::make(User::find($user_id)), ['token' => compact('expires_in')]);
    }
}
