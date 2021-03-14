<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\Role */
class RoleResource extends ModelResource
{
    public function transformTo(): array
    {
        return [
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
