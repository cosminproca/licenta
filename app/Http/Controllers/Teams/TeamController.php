<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\InviteTeamMemberRequest;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

class TeamController extends Controller
{
    private array $relations = [
        'owner',
        'users',
        'invites'
    ];

    public function __construct()
    {
        $this->authorizeResource(Team::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(TeamResource::collection(Team::where('owner_id', auth()->user()->id)->get()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreTeamRequest  $request
     * @return JsonResponse
     */
    public function store(StoreTeamRequest $request)
    {
        $validated_data = $request->validated();
        $team = Team::create($validated_data);

        $request->user()->attachTeam($team);

        $status = $team->save();

        return response()->json([
            'status' => $status,
            'data' => new TeamResource($team->load($this->relations))
        ]);
    }


    /**
     * Switch to the given team.
     *
     * @param  Team $team
     * @return JsonResponse
     */
    public function switchTeam(Team $team)
    {
        try {
            $status = auth()->user()->switchTeam($team);
        } catch (UserNotInTeamException $e) {
            return response()->json([
                'error' => 'Authenticated user does not belong to any teams.'
            ], 403);
        }

        return response()->json([
            'status' => $status
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Team  $team
     * @return JsonResponse
     */
    public function show(Team $team)
    {
        return response()->json(new TeamResource($team->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateTeamRequest  $request
     * @param  Team  $team
     * @return JsonResponse
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        $validated_data = $request->validated();

        $status = $team->update($validated_data);

        return response()->json([
            'status' => $status,
            'data' => new TeamResource($team->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Team  $team
     * @return JsonResponse
     */
    public function destroy(Team $team)
    {
        $status = $team->delete();
        auth()->user()->detachTeam($team);

        return response()->json([
            'status' => $status
        ]);
    }

    /**
     * Invite User to a team.
     *
     * @param InviteTeamMemberRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function invite(InviteTeamMemberRequest $request, Team $team)
    {
        $validated_data = $request->validated();

        if (! Teamwork::hasPendingInvite($validated_data['email'], $team)) {
            Teamwork::inviteToTeam($validated_data['email'], $team, function ($invite) {
                Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
                    $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
                });
            });
        } else {
            return response()->json([
                'status' => false,
                'message' => 'The email address is already invited to the team.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invitation email sent successfully.'
        ]);
    }

    /**
     * Resend an invitation mail.
     *
     * @param $invite_id
     * @return JsonResponse
     */
    public function resendInvite($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);

        Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
            $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
        });

        return response()->json([
            'status' => true,
            'message' => 'Invitation email resent successfully.'
        ]);
    }
}
