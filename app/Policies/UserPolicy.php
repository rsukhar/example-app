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
        // Могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь просматривать другого пользователя
     */
    public function view(User $authUser, string $username): bool
    {
        // Могут только админы, либо если пользователь редактирует себя
        return ($authUser->role === 'admin' or $authUser->username === $username);
    }

    /**
     * Может ли пользователь создавать других пользователей
     */
    public function create(User $authUser): bool
    {
        // Могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь редактировать другого пользователя
     */
    public function update(User $authUser, string $username): bool
    {
        // Могут только админы, либо если пользователь редактирует себя
        return ($authUser->role === 'admin' or $authUser->username === $username);
    }

    /**
     * Может ли пользователь удалять другого пользователя
     */
    public function delete(User $authUser, string $username): bool
    {
        // Могут только админы
        return ($authUser->role === 'admin');
    }
}
