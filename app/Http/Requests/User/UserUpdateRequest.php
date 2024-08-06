<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $userRoles = array_keys(config('models.users.roles', []));

        return [

            'name' => 'sometimes|string|nullable|max:255',
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique(User::class, 'username')->ignore($this->id),
            ],
            'email' => [
                'sometimes',
                'email',
                'nullable',
                Rule::unique(User::class, 'email')->ignore($this->id),
            ],
            'password' => 'sometimes|string|max:255',
            'is_blocked' => 'sometimes|boolean',
            'role' => [
                'sometimes',
                Rule::in($userRoles),
            ],
            'birthday' => 'sometimes|string|nullable',
        ];
    }
}
