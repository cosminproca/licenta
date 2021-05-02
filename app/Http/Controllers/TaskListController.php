<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;
use Illuminate\Http\JsonResponse;

class TaskListController extends Controller
{
    private $relations = [
        'tasks',
        'tasks.subtasks',
        'tasks.assignee'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(TaskListResource::collection(TaskList::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTaskListRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskListRequest $request)
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
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function show(TaskList $taskList)
    {
        return response()->json(new TaskListResource($taskList->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskListRequest $request
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function update(UpdateTaskListRequest $request, TaskList $taskList)
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
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function destroy(TaskList $taskList)
    {
        return response()->json([
            'status' => $taskList->delete()
        ]);
    }
}
