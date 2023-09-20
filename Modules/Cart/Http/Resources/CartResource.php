<?php

namespace Modules\Cart\Http\Resources;

use App\Resources\BaseResource;
use Modules\Product\Http\Resources\ProductResource;
use Modules\User\Http\Resources\UserResource;

class CartResource extends BaseResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id'  => $this->user_id,
            'product_id' => $this->product_id,
            'product'  => ProductResource::make($this->whenLoaded('product')),
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
