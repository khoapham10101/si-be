<?php

namespace Modules\User\Http\Requests;

use App\Traits\PaginationRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
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
        return [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'id_card' => 'required|string',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|numeric',
            'id_1' => 'nullable|string',
            'id_2' => 'nullable|string',
            'avatar' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'user_status_id ' => 'required|numeric'
        ];
    }

}
