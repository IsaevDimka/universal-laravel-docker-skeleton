<?php

declare(strict_types=1);

namespace App\Http\Resources;

/** @mixin \App\Models\Currency */
class CurrencyResource extends ModelResource
{
    public function transformTo(): array
    {
        return [];
    }
}
