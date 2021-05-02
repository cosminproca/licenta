<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Task extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'task_list_id',
        'assignee_id',
        'name',
        'description',
        'priority',
        'time',
        'time_estimation',
        'due_date'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }
}
