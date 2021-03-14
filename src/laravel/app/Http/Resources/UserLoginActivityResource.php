<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\UserLoginActivity */
class UserLoginActivityResource extends ModelResource
{
    public function transformTo(): array
    {
        return [];
    }
}
