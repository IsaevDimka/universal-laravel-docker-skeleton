<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

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
use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class UserController extends ApiController
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $permission = Arr::get($searchParams, 'permission', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($permission)) {
            $userQuery->whereHas('permissions', function($q) use ($permission) { $q->where('name', $permission); });
        }

        if (!empty($keyword)) {
            $userQuery->orWhere('username', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('email', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('first_name', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
        }

        $total = $userQuery->count();

        return api()->ok('', UserResource::collection($userQuery->paginate($limit))->toArray($request), compact('total'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                    'password'          => ['required', 'min:6'],
                    'confirm_password'  => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return api()->validation('', $validator->errors()->toArray());
        } else {
            $params = $request->all();
            /** @var User $user */
            $user = User::create([
                'username' => $params['username'],
                'email'    => $params['email'],
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
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && $currentUser->id !== $user->id
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_USER_MANAGE)
        ) {
            return api()->forbidden('Permission denied');
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return api()->validation('', $validator->errors()->toArray());
        } else {
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
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
            function($permission) {
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
     * @param  User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return api()->forbidden('', [], ['error' => 'Ehhh! Can not delete admin user']);
        }

        if ($user->isRoot()) {
            return api()->forbidden('', [], ['error' => 'Ehhh! Can not delete root user']);
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
     * @param User $user
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
                'array'
            ],
            'permissions' => [
                'nullable',
                'array'
            ],
        ];
    }
}
