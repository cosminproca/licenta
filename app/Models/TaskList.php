<?php

namespace App\Models;

use App\Traits\TeamOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory, TeamOwned;

    protected $fillable = [
        'team_id',
        'name'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
