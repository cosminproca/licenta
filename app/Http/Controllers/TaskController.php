<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private $relations = [
        'taskList',
        'subTasks',
        'subTasks.assignee'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(TaskResource::collection(Task::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validated_data = $request->validated();
        $task = Task::create($validated_data);

        $status = $task->save();

        return response()->json([
            'status' => $status,
            'data' => new TaskResource($task->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task)
    {
        return response()->json(new TaskResource($task->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated_data = $request->validated();

        $status = $task->update($validated_data);

        return response()->json([
            'status' => $status,
            'data' => new TaskResource($task->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task)
    {
        return response()->json([
            'status' => $task->delete()
        ]);
    }
}