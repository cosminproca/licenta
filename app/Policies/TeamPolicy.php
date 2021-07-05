<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }

    public function delete(User $user, Team $team): bool
    {
        return checkIfUserBelongsToTeam($team->id);
    }
}
