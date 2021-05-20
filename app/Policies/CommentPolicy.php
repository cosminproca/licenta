<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment, Team $team): bool
    {
        return $comment->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }

    public function create(User $user, Comment $comment, Team $team): bool
    {
        return $comment->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }

    public function update(User $user, Comment $comment, Team $team): bool
    {
        return $comment->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }

    public function delete(User $user, Comment $comment, Team $team): bool
    {
        return $comment->team_id === $team->id || checkIfUserBelongsToTeam($team->id);
    }
}
