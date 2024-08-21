<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'is_active',
        'assignee_id',
        'deadline_date',
    ];

    protected $casts = [
        'author_id' => 'integer',
        'assignee_id' => 'integer',
        'deadline_date' => 'date'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'assignee_id');
    }

    /**
     * Добавляет к запросу условие «дедлайн истёк»
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('deadline_date', '<', date('Y-m-d'));
    }
}
