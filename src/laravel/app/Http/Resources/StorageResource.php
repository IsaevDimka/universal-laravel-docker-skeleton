<?php

namespace App\Http\Resources;

use App\Models\BaseModel;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageResource extends JsonResource
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
            'id'         => $this->id,
            'filename'   => $this->filename,
            'type'       => $this->type,
            'path'       => $this->path,
            'size'       => $this->size,
            'url'        => $this->url,
            'user_id'    => $this->user_id,
            'user'       => $this->user ?: UserResource::make($this->user),
            'created_at' => BaseModel::formattingCarbonAttribute($this->created_at),
            'updated_at' => BaseModel::formattingCarbonAttribute($this->updated_at),
            'deleted_at' => BaseModel::formattingCarbonAttribute($this->deleted_at),
        ];
    }
}
