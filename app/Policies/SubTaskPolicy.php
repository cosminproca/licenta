<?php

namespace App\Policies;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubTaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }

    public function view(User $user, SubTask $subTask, Team $team): bool
    {
        return $subTask->team_id === $team->id;
    }

    public function create(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }

    public function update(User $user, SubTask $subTask, Team $team): bool
    {
        return $subTask->team_id === $team->id;
    }

    public function delete(User $user, SubTask $subTask, Team $team): bool
    {
        return $subTask->team_id === $team->id;
    }
}
