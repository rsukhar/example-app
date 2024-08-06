<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'username',
        'first_name',
        'email',
        'phone',
        'password',
        'role',
        'is_blocked'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:Y-m-d H:i:s',
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
}
