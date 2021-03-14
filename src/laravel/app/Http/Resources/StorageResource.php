<?php

declare(strict_types=1);

namespace App\Http\Resources;

class StorageResource extends ModelResource
{
    public function transformTo(): array
    {
        return [
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
