<?php


namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * @todo: need improve
     */

    const ROLE_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_EDITOR = 'editor';
    const ROLE_USER = 'user';
    const ROLE_VISITOR = 'visitor';

    public $guard_name = 'web';

}
