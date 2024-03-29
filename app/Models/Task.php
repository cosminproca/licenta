<?php

namespace App\Models;

use App\Traits\TeamOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Tags\HasTags;

class Task extends Model implements Sortable
{
    use HasFactory, HasTags, TeamOwned, SortableTrait;

    protected $fillable = [
        'task_list_id',
        'assignee_id',
        'completed',
        'name',
        'description',
        'priority',
        'time',
        'time_estimation',
        'due_date',
        'order_column'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class)->orderBy('order_column');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }
}
