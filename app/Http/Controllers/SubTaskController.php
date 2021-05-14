<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubTaskRequest;
use App\Http\Requests\UpdateSubTaskRequest;
use App\Http\Resources\SubTaskResource;
use App\Models\SubTask;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class SubTaskController extends Controller
{
    private $relations = [
        'task',
        'assignee',
        'tags'
    ];

    public function __construct()
    {
        $this->authorizeResource(SubTask::class, 'subTask,team');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team)
    {
        return response()->json(SubTaskResource::collection(SubTask::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Team $team
     * @param StoreSubTaskRequest $request
     * @return JsonResponse
     */
    public function store(Team $team, StoreSubTaskRequest $request)
    {
        $validated_data = $request->validated();

        $subTask = SubTask::create($validated_data);

        $subTask->syncTags($validated_data['tags']);

        $status = $subTask->save();

        return response()->json([
            'status' => $status,
            'data' => new SubTaskResource($subTask->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @param SubTask $subTask
     * @return JsonResponse
     */
    public function show(Team $team, SubTask $subTask)
    {
        return response()->json(new SubTaskResource($subTask->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Team $team
     * @param UpdateSubTaskRequest $request
     * @param SubTask $subTask
     * @return JsonResponse
     */
    public function update(Team $team, UpdateSubTaskRequest $request, SubTask $subTask)
    {
        $validated_data = $request->validated();

        $status = $subTask->update($validated_data);

        $subTask->syncTags($validated_data['tags']);

        return response()->json([
            'status' => $status,
            'data' => new SubTaskResource($subTask->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @param SubTask $subTask
     * @return JsonResponse
     */
    public function destroy(Team $team, SubTask $subTask)
    {
        return response()->json([
            'status' => $subTask->delete()
        ]);
    }
}
