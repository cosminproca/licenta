<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task, Team $team): bool
    {
        return $task->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }

    public function create(User $user, Task $task, Team $team): bool
    {
        return $task->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }

    public function update(User $user, Task $task, Team $team): bool
    {
        return $task->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }

    public function delete(User $user, Task $task, Team $team): bool
    {
        return $task->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }
}
