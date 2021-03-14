<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\UserLoginActivityResource;
use App\Models\UserLoginActivity;
use Illuminate\Http\Request;

class UserLoginActivityController extends ApiController
{
    public function index(Request $request)
    {
        return api()->ok(null, UserLoginActivityResource::collection(UserLoginActivity::forCurrentUser()->paginate($request->get('limit', 30))));
    }
}
