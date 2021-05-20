<?php

namespace App\Models;

use App\Traits\TeamOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, TeamOwned;

    protected $fillable = [
        'team_id',
        'user_id',
        'task_id',
        'text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
