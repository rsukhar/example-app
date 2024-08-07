<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ];
    }


    public function messages()
    {
        return [
            'username' => 'Не заполнено имя пользователя',
            'password' => 'Неверный пароль',
        ];
    }
}
