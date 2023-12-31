<?php

namespace Modules\User\Http\Requests;

use App\Traits\PaginationRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\CheckBirthDay;

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
            'id_card' => 'sometimes|required|string',
            'birthday' => ['nullable', 'date', new CheckBirthDay],
            'gender_id' => 'nullable|numeric',
            'id_1' => 'nullable|string',
            'id_2' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'user_status_id' => 'sometimes|required|numeric'
        ];
    }
}
