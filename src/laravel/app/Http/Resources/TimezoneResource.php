<?php

declare(strict_types=1);

namespace App\Http\Resources;

/**
 * Class TimezoneResource
 *
 * @package App\Http\Resources
 * @mixin \App\Models\Timezone
 */
class TimezoneResource extends ModelResource
{
    public function transformTo(): array
    {
        return [];
    }
}
