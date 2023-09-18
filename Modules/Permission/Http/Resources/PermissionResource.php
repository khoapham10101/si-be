<?php

namespace Modules\Permission\Http\Resources;

use App\Resources\BaseResource;

class PermissionResource extends BaseResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'module_name'   => $this->module_name,
            'name'          => $this->name,
            'action'        => $this->action
        ];
    }
}
