<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private $relations = [
        'taskList',
        'subTasks',
        'subTasks.assignee',
        'tags'
    ];

    public function __construct()
    {
        //$this->authorizeResource(Task::class, 'task,team');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team)
    {
        return response()->json(TaskResource::collection(Task::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Team $team
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(Team $team, StoreTaskRequest $request)
    {
        $validated_data = $request->validated();
        $task = Task::create($validated_data);

        if(isset($validated_data['tags'])) {
            $task->syncTags($validated_data['tags']);
        }

        $status = $task->save();

        return response()->json([
            'status' => $status,
            'data' => new TaskResource($task->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Team $team, Task $task)
    {
        return response()->json(new TaskResource($task->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Team $team
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Team $team, UpdateTaskRequest $request, Task $task)
    {
        $validated_data = $request->validated();

        $status = $task->update($validated_data);

        if(isset($validated_data['tags'])) {
            $task->syncTags($validated_data['tags']);
        }

        return response()->json([
            'status' => $status,
            'data' => new TaskResource($task->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Team $team, Task $task)
    {
        return response()->json([
            'status' => $task->delete()
        ]);
    }
}
