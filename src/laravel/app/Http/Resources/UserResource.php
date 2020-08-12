<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'username'          => $this->username,
            'email'             => $this->email,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'telegram_chat_id'  => $this->telegram_chat_id,
            'email_verified_at' => $this->email_verified_at,
            'last_visit_at'     => $this->last_visit_at,
            'is_active'         => $this->is_active,
            'locale'            => $this->locale,
            'options'           => $this->options,
            'roles'             => $this->getRoleNames()->toArray(),
            'permissions'       => $this->getPermissionNames()->toArray(),
            'avatar'            => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif?imageView2/1/w/80/h/80',
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
