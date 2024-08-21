<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Может ли пользователь просматривать список проектов
     */
    public function viewAll(User $user): bool
    {
        return true;
    }

    /**
     * Может ли пользователь просматривать содержимое проекта другого пользователя
     */
    public function view(User $user, Project $project): bool
    {
        return ($user->role === 'admin' 
                || $user->id === $project->author_id
                || $user->id === $project->assignee_id
            );
    }
    
    /**
     * Может ли пользователь создавать проекты
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Может ли пользователь редактировать проект
     */
    public function update(User $user, Project $project): bool
    {
        return ($user->role === 'admin' || $user->id === $project->author_id);
    }

    /**
     * Может ли пользователь удалить проект
     */
    public function delete(User $user, Project $project): bool
    {
        return ($user->role === 'admin' || $user->id === $project->author_id);
    }
}
