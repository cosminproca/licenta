<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpociot\Teamwork\TeamworkTeam;

class Team extends TeamworkTeam
{
    use HasFactory;

    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
