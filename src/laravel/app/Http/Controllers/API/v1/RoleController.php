<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends ApiController
{
    public function index()
    {
        return api()->ok(null, RoleResource::collection(Role::query()->with('permissions')->get()));
    }

    public function store(Request $request)
    {
        return api()->ok();
    }

    public function show(Role $role)
    {
        return api()->ok(null, RoleResource::make($role));
    }

    public function update(Request $request, Role $role)
    {
        if ($role === null || $role->isAdmin()) {
            return api()->notFound('Role not found');
        }

        $permissionIds = $request->get('permissions', []);
        $permissions = Permission::allowed()->whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
        $role->save();
        return api()->ok(null, RoleResource::make($role));
    }

    public function destroy($id)
    {
        //
    }

    public function permissions(Role $role)
    {
        return PermissionResource::collection($role->permissions);
    }
}
