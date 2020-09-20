<?php

namespace App\Http\Resources;

use App\Models\BaseModel;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'title'      => $this->title,
            'slug'       => $this->slug,
            'content'    => $this->content,
            'is_active'  => $this->is_active,
            'author'     => [
                'id'         => $this->author->id,
                'username'   => $this->author->username,
                'first_name' => $this->author->first_name,
                'last_name'  => $this->author->last_name,
            ],
            'created_at' => BaseModel::formattingCarbonAttribute($this->created_at),
            'updated_at' => BaseModel::formattingCarbonAttribute($this->updated_at),
            'deleted_at' => BaseModel::formattingCarbonAttribute($this->deleted_at),
        ];
    }
}
