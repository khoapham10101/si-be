<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreate extends FormRequest
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
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'id_card' => 'required|string',
            'birthday' => 'nullable|date',
            'gender_id' => 'nullable|numeric',
            'id_1' => 'nullable|string',
            'id_2' => 'nullable|string',
            'avatar' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'user_status_id' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:20|regex:/^[A-Za-z\d@$!%*?&.]{8,}$/',
        ];
    }
}
