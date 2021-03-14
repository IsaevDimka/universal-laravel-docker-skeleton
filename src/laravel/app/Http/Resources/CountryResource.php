<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\Country */
class CountryResource extends ModelResource
{
    public function transformTo(): array
    {
        return [];
    }
}
