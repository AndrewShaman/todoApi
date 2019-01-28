<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task()
    {
        return $this->hasMany(Task::class,'project_id');
    }
}
