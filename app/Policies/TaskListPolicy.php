<?php

namespace App\Policies;

use App\Models\TaskList;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskListPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }

    public function view(User $user, TaskList $taskList, Team $team): bool
    {
        return $taskList->team_id === $team->id;
    }

    public function create(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }

    public function update(User $user, TaskList $taskList, Team $team): bool
    {
        return $taskList->team_id === $team->id;
    }

    public function delete(User $user, TaskList $taskList, Team $team): bool
    {
        return $taskList->team_id === $team->id;
    }
}
