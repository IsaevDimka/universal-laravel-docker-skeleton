<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\Permission */
class PermissionResource extends ModelResource
{
    public function transformTo(): array
    {
        return [];
    }
}
