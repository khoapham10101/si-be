<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Product;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Product $product)
    {
        return [
            'name' => 'required|string',
            'brand_id' => 'required|numeric',
            'sku' => [
                'required',
                'string',
                Rule::unique($product->getConnectionName() . '.' . $product->getTable()),
            ],
            'description' => 'nullable',
            'warranty_information' => 'nullable',
            'quantity' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'images' => 'nullable'
        ];
    }
}
