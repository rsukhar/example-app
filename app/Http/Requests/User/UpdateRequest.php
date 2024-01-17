<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Применяет правила валидации к запросу
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userRoles = array_keys(config('models.users.roles', ['admin' => 'Админ']));

        return [

            'name' => 'sometimes|string|nullable|max:255',
            'username' => [
                'required',
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
