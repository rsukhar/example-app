<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $userRoles = array_keys(config('models.users.roles', []));

        return [
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'sometimes|email|nullable|unique:users,email',
            'password' => 'required|string|max:255',
            'first_name' => 'sometimes|string|nullable|max:255',
            'is_blocked' => 'sometimes|boolean',
            'role' => [
                'required',
                Rule::in($userRoles),
            ],
            'birthday' => 'sometimes|string|nullable',
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => 'Логин уже существует',
            'username' => 'Не заполнен логин',
            'email' => 'Неверный формат Email-адреса',
            'password' => 'Не заполнен пароль',
        ];
    }
}
