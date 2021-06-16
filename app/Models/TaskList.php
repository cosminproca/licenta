<?php

namespace App\Models;

use App\Traits\TeamOwned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class TaskList extends Model implements Sortable
{
    use HasFactory, TeamOwned, SortableTrait;

    protected $fillable = [
        'team_id',
        'name',
        'due_date',
        'order_column'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order_column');
    }
}
