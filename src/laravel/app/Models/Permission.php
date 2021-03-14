<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Query\Builder;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission allowed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $guardName
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read int|null $permissionsCount
 * @property-read int|null $rolesCount
 * @property-read int|null $usersCount
 * @mixin IdeHelperPermission
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * @todo: need improve
     */
    public const MANAGE_PERMISSION = 'manage permission';

    public const MANAGE_USERS = 'manage users';

    public $guard_name = 'web';

    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', self::MANAGE_PERMISSION);
    }
}
