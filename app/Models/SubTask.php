<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class SubTask extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'task_id',
        'assignee_id',
        'name',
        'description',
        'due_date'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }
}
