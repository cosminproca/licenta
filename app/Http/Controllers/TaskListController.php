<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class TaskListController extends Controller
{
    private $relations = [
        'tasks',
        'tasks.subtasks',
        'tasks.assignee'
    ];

    public function __construct()
    {
        $this->authorizeResource(TaskList::class, 'taskList,team');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team)
    {
        return response()->json(TaskListResource::collection(TaskList::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Team $team
     * @param StoreTaskListRequest $request
     * @return JsonResponse
     */
    public function store(Team $team, StoreTaskListRequest $request)
    {
        $validated_data = $request->validated();
        $taskList = TaskList::create($validated_data);

        $status = $taskList->save();

        return response()->json([
            'status' => $status,
            'data' => new TaskListResource($taskList->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function show(Team $team, TaskList $taskList)
    {
        return response()->json(new TaskListResource($taskList->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Team $team
     * @param UpdateTaskListRequest $request
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function update(Team $team, UpdateTaskListRequest $request, TaskList $taskList)
    {
        $validated_data = $request->validated();
        $status = $taskList->update($validated_data);

        return response()->json([
            'status' => $status,
            'data' => new TaskListResource($taskList->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function destroy(Team $team, TaskList $taskList)
    {
        return response()->json([
            'status' => $taskList->delete()
        ]);
    }
}
