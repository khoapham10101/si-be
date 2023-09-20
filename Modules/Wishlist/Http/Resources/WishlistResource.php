<?php

namespace Modules\Wishlist\Http\Resources;

use App\Resources\BaseResource;
use Modules\Product\Http\Resources\ProductResource;
use Modules\User\Http\Resources\UserResource;

class WishlistResource extends BaseResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'product_id' => $this->product_id,
            'product' => ProductResource::make($this->whenLoaded('product')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->date($this->created_at),
            'updated_at_formatted' => $this->date($this->updated_at),
        ];
    }
}
