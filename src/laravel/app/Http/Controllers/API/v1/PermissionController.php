<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\PermissionResource;

use App\Models\Permission;

class PermissionController extends ApiController
{
    public function index()
    {
        return api()->ok(null, PermissionResource::collection(Permission::query()->with('roles')->get()));
    }
}
