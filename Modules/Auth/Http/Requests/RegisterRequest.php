<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'id_card' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'birthday' => 'required|date-format:Y/m/d',
            'gender_id' => 'required|numeric|exists:genders,id',
            'password' => 'required|min:8|max:20|regex:/^[A-Za-z\d@$!%*?&.]{8,}$/'
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => '8-20 characters, at least 01 special & uppercase, number & without space',
        ];
    }
}
