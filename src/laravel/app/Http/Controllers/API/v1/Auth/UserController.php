<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;

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
        $this->dispatch(new \App\Jobs\UserUpdateLastVisitAt($user->id));
        return api()->ok(null, (new UserResource($user)));
    }
}
