<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Может ли пользователь просматривать список пользователей
     */
    public function viewAll(User $authUser): bool
    {
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь просматривать другого пользователя
     */
    public function view(User $authUser, User $viewedUser): bool
    {
        return ($authUser->role === 'admin' or $authUser->id === $viewedUser->id);
    }

    /**
     * Может ли пользователь создавать других пользователей
     */
    public function create(User $authUser): bool
    {
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь редактировать базовые параметры другого пользователя (email, пароль и тп)
     */
    public function update(User $authUser, User $updatedUser): bool
    {
        return ($authUser->role === 'admin' or $authUser->id === $updatedUser->id);
    }

    /**
     * Может ли пользователь редактировать расширенные параметры другого пользователя (роль, блокировка и тп)
     */
    public function updateAll(User $authUser, User $updatedUser): bool
    {
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь удалять другого пользователя
     */
    public function delete(User $authUser, User $deletedUser): bool
    {
        return ($authUser->role === 'admin');
    }
}
