<?php

namespace App\Models;

use App\Traits\TeamOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Tags\HasTags;

class SubTask extends Model implements Sortable
{
    use HasFactory, HasTags, TeamOwned, SortableTrait;

    protected $fillable = [
        'task_id',
        'assignee_id',
        'name',
        'description',
        'due_date',
        'order_column'
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
