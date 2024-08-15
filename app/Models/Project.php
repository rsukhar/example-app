<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [
        ''
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function assignee()
    {
        return $this->belongsTo('App\Models\User', 'assignee_id');
    }
}
