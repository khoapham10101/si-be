<?php

namespace Modules\Product\Http\Requests;

use App\Traits\PaginationRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ListProductRequest extends FormRequest
{
    use PaginationRequestTrait;

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
    public function rules()
    {
        return array_merge($this->paginationRules(), []);
    }
}
