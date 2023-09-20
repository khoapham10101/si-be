<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Cart\Entities\Cart;

class AddToCartUpdateRequest extends FormRequest
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
    public function rules(Cart $cart)
    {
        return [
            'cart_items'         => [
                'required'
            ],
            'cart_items.*.cart_id' => [
                'required',
                Rule::exists($cart->getTable(), $cart->getKeyName())
            ],
            'cart_items.*.quantity' => [
                'required'
            ]
        ];
    }
}
