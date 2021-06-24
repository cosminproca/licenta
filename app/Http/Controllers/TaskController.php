<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateAllTaskSubTasksRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private array $relations = [
        'taskList',
        'subTasks',
        'subTasks.assignee',
        'tags'
    ];

    public function __construct()
    {
        $this->authorizeResource('App\Models\Team,task', 'team,task');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team): JsonResponse
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
    public function store(Team $team, StoreTaskRequest $request): JsonResponse
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
    public function show(Team $team, Task $task): JsonResponse
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
    public function update(Team $team, UpdateTaskRequest $request, Task $task): JsonResponse
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
     * Update all resources in storage.
     *
     * @param Team $team
     * @param UpdateAllTaskSubTasksRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function updateAll(Team $team, UpdateAllTaskSubTasksRequest $request, Task $task): JsonResponse
    {
        $validated_data = $request->validated();

        $sub_tasks = SubTask::findMany(collect($validated_data['sub_tasks'])->pluck('id'));

        foreach ($sub_tasks as $sub_task) {
            $sub_task->timestamps = false;

            foreach ($validated_data['sub_tasks'] as $subTaskFrontEnd) {
                if ($subTaskFrontEnd['id'] == $task->id) {
                    $sub_task->update(['order_column' => $subTaskFrontEnd['order_column']]);
                    $sub_task->save();
                }
            }
        }

        $task->subTasks()->saveMany($sub_tasks);
        $task->save();
        $task->refresh();

        return response()->json(new TaskResource($task->load($this->relations)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Team $team, Task $task): JsonResponse
    {
        return response()->json([
            'status' => $task->delete()
        ]);
    }
}
