<?php

namespace Modules\User\Http\Resources;

use App\Resources\BaseResource;
use Modules\Gender\Http\Resources\GenderResource;
use Modules\Role\Http\Resources\RoleResource;
use Modules\UserStatus\Http\Resources\UserStatusResource;

class UserResource extends BaseResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'id_card' => $this->id_card,
            'birthday' => $this->birthday,
            'gender_id' => $this->gender_id,
            'gender' => GenderResource::make($this->whenLoaded('gender')),
            'id_1' => $this->id_1,
            'id_2' => $this->id_2,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'phone' => $this->phone,
            'address' => $this->address,
            'user_status_id' => $this->user_status_id,
            'user_status' => UserStatusResource::make($this->whenLoaded('userStatus')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->date($this->created_at),
            'updated_at_formatted' => $this->date($this->updated_at),
        ];
    }
}
