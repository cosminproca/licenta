<?php

function getTeamKey()
{
    $uri = explode('/', request()->getRequestUri());

    $key = array_search('teams', $uri, true);

    return (int) $uri[$key + 1];
}

function checkIfUserBelongsToTeam($team_id)
{
    return in_array($team_id, auth()->user()->teams->pluck('id')->toArray(), true);
}
