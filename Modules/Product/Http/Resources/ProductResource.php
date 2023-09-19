<?php

namespace Modules\Product\Http\Resources;

use App\Resources\BaseResource;
use Modules\Brand\Http\Resources\BrandResource;

class ProductResource extends BaseResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku'   => $this->sku,
            'brand_id' => $this->brand_id,
            'brand'    => BrandResource::make($this->whenLoaded('brand')),
            'description'   => $this->description,
            'price' => $this->price,
            'quantity'  => $this->quantity,
            'warranty_information'  => $this->warranty_information,
            'images' => $this->product_images,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->date($this->created_at),
            'updated_at_formatted' => $this->date($this->updated_at),
        ];
    }
}
