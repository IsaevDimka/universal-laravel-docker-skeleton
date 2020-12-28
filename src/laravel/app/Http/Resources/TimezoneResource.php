<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TimezoneResource
 *
 * @package App\Http\Resources
 * @mixin \App\Models\Timezone
 */
class TimezoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'timezone' => $this->timezone,
            'name'     => $this->name,
            'offset'   => $this->offset,
        ];
    }
}
