<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Brand\Entities\Brand;
use Modules\Product\Entities\Product;

class UpdateProductRequest extends FormRequest
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
    public function rules(Request $request, Product $product, Brand $brand)
    {
        return [
            'name' => 'required|string',
            'brand_id' => [
                'required',
                'numeric',
                Rule::exists($brand->getTable(), $brand->getKeyName())->withoutTrashed(),
            ],
            'sku' => [
                'required',
                'string',
                Rule::unique($product->getConnectionName() . '.' . $product->getTable())
                    ->ignore($request->route('productId'), 'id'),
            ],
            'description' => 'nullable',
            'warranty_information' => 'nullable',
            'delivery_infomation' => 'nullable',
            'quantity' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'id_2' => 'nullable|string',
            'images' => 'nullable'
        ];
    }
}
