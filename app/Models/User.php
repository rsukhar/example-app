<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Массив скрытых полей
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Массив заполняемых полей
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'username',
        'password',
        'role',
        'birthday',
        'is_blocked'
    ];

    /**
     * Преобразование значений
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'datetime:d.m.Y',
        ];
    }

    /**
     * Фильтрация
     *
     * @param Builder $query
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $query
            // Перечисление полей для фильтрации
            ->when(isset($filters['role']), function ($query) use ($filters) {
                $query->where('role', $filters['role']);
            })
            ->when(isset($filters['q']), function ($query) use ($filters) {
                $q = $filters['q'];

                if ($q !== null and $q !== '') {
                    if (str_starts_with($q, '@')) {
                        $query->where('username', ltrim($q, '@'));
                    } elseif (str_contains($q, '@')) {
                        $query->where('email', 'like', "%{$q}%");
                    } else {
                        $query->where('username', 'like', "%{$q}%");
                    }
                }
            });

        return $query;
    }

    /**
     * Получить список ролей пользователей для фильтра
     *
     * @param bool $showDefaultRole
     *
     * @return array
     */
    public static function getUserRoles(bool $showDefaultRole = true): array
    {
        // Значения полей для фильтров выносим в config models.php
        return [
            'slugs' => array_merge($showDefaultRole ? [''] : [], array_keys(config('models.users.roles'))),
            'values' => array_merge($showDefaultRole ? ['' => 'Все'] : [], config('models.users.roles'))
        ];
    }
}
