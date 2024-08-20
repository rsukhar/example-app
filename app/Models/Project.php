<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'assignee_id',
        'deadline_date',
    ];

    protected $casts = [
        'author_id' => 'integer',
        'assignee_id' => 'integer',
        'deadline_date' => 'date'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function assignee()
    {
        return $this->belongsTo('App\Models\User', 'assignee_id');
    }
}
