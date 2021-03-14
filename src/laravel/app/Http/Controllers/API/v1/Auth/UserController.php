<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\UserResource;
use App\Jobs\UserLoginActivityStoreJob;
use App\Jobs\UserUpdateLastVisitAt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * Get authenticated user.
     */
    public function current(Request $request)
    {
        $user_id = $request->user()->id;
        /** @var User $user */
        $user = Auth::user();

        dispatch(new UserUpdateLastVisitAt($user_id));
        dispatch(new UserLoginActivityStoreJob($user_id, $request->getClientIp(), $request->userAgent()));

        $expiration = Auth::guard()->getPayload()->get('exp');
        $expires_in = $expiration - time();
        $user->append(['role_names', 'permission_names', 'jwt']);

        return api()->ok(null, UserResource::make($user), [
            'token' => compact('expires_in'),
        ]);
    }
}
