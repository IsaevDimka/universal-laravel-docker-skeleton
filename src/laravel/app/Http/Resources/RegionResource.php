<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\Region */
class RegionResource extends ModelResource
{
    public function transformTo(): array
    {
        return [];
    }
}
