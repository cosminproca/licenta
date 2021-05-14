<?php

namespace App\Providers;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\TaskList;
use App\Policies\SubTaskPolicy;
use App\Policies\TaskListPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Team;
use App\Policies\TeamPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        TaskList::class => TaskListPolicy::class,
        Task::class => TaskPolicy::class,
        SubTask::class => SubTaskPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
