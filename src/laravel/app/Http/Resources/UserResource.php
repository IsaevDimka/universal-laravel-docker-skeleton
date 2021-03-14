<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\User */
class UserResource extends ModelResource
{
    public function transformTo(): array
    {
        return [
            'roles' => $this->whenAppended('role_names'),
            'permissions' => $this->whenAppended('permission_names'),
            'jwt' => $this->whenAppended('jwt'),
        ];
    }
}
