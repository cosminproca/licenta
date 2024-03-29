<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTeam
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(checkIfUserBelongsToTeam(getTeamKey())) {
            return $next($request);
        }

        return response()->json(['error' => 'Authenticated user does not belong to this team.'], 403);
    }
}
