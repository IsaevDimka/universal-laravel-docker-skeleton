<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class UserController extends ApiController
{
    /**
     * Display a listing of the user resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $queryBuilder = User::query();
        $role = Arr::get($searchParams, 'role', null);
        $permission = Arr::get($searchParams, 'permission', null);
        $keyword = Arr::get($searchParams, 'keyword', null);

        $queryBuilder->when(! empty($role), fn ($q) => $q->whereHas('roles', fn ($q) => $q->where('name', $role)));

        $queryBuilder->when(! empty($permission), fn ($q) => $q->whereHas('permissions', fn ($q) => $q->where('name', $permission)));

        $queryBuilder->when(
            ! empty($keyword),
            fn ($q) =>
            $q->orWhere('username', 'LIKE', '%' . $keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                ->orWhere('first_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('last_name', 'LIKE', '%' . $keyword . '%')
        );

        $items = UserResource::items($queryBuilder->paginate($request->get('limit')));
        return api()->ok(null, compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'password' => ['required', 'min:6'],
                    'confirm_password' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return api()->validation('', $validator->errors()->toArray());
        }
        $params = $request->all();
        /** @var User $user */
        $user = User::create([
            'username' => $params['username'],
            'email' => $params['email'],
            'password' => $params['password'],
        ]);

        $role = Role::findByName($params['role']);
        $user->syncRoles($role);

        if ($params['permission']) {
            $permission = Permission::findByName($params['permission']);
            $user->syncPermissions($permission);
        }

        return api()->ok('', (new UserResource($user)));
    }

    /**
     * Display the specified resource.
     *
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return api()->ok(null, UserResource::make($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        }
        if ($user->isAdmin()) {
            return response()->json([
                'error' => 'Admin can not be modified',
            ], 403);
        }

        $currentUser = Auth::user();
        if (! $currentUser->isAdmin()
            && $currentUser->id !== $user->id
            && ! $currentUser->hasPermission(Permission::MANAGE_USERS)
        ) {
            return api()->forbidden('Permission denied');
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return api()->validation('', $validator->errors()->toArray());
        }
        $email = $request->get('email');
        $found = User::where('email', $email)->first();
        if ($found && $found->id !== $user->id) {
            return api()->forbidden('Email has been taken');
        }

        $user->name = $request->get('name');
        $user->email = $email;
        $user->save();
        return api()->ok('', new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return api()->notFound('User not found');
        }

        if ($user->isAdmin()) {
            return api()->forbidden('Admin can not be modified');
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function ($permission) {
                return $permission['id'];
            },
            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);

        return api()->ok('', (new UserResource($user)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return api()->forbidden('', [], [
                'error' => 'Ehhh! Can not delete admin user',
            ]);
        }

        if ($user->isRoot()) {
            return api()->forbidden('', [], [
                'error' => 'Ehhh! Can not delete root user',
            ]);
        }

        try {
            $user->delete();
        } catch (\Exception $ex) {
            return api()->error($ex->getMessage());
        }

        return api()->ok();
    }

    /**
     * Get permissions from role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions(User $user)
    {
        try {
            return api()->ok('', [
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            return api()->error($ex->getMessage());
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'username' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
        ];
    }
}
