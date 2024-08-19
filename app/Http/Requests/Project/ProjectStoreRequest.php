<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'assignee_id' => ['required', 'int', 'exists:users,id'],
            'deadline_date' => ['required', 'date']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Проекту нужно название',
            'assignee_id.exists' => 'Выбранный ответственный пользователь не существует',
            'deadline_date.required' => 'Установите дедлайн',
        ];
    }
}
