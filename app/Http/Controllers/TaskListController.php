<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateAllTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Http\Resources\TaskListResource;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskListController extends Controller
{
    private array $relations = [
        'tasks',
        'tasks.subtasks',
        'tasks.assignee'
    ];

    public function __construct()
    {
        //$this->authorizeResource(TaskList::class, 'task_list,teams');
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
        //$this->authorize('store', [TaskList::class, $team]);

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
     * Update all resources in storage.
     *
     * @param Team $team
     * @param UpdateAllTaskListRequest $request
     * @param TaskList $taskList
     * @return JsonResponse
     */
    public function updateAll(Team $team, UpdateAllTaskListRequest $request, TaskList $taskList)
    {
        $validated_data = $request->validated();
        //$status = $taskList->update($validated_data);

        $task_lists = TaskList::all()->load($this->relations);

        foreach ($task_lists as $task_list) {
            $task_list->timestamps = false;
            $id = $task_list->id;

            foreach ($validated_data['task_lists'] as $taskListFrontEnd) {
                if ($taskListFrontEnd['id'] == $id) {
                    $task_list->update(['order_column' => $taskListFrontEnd['order_column']]);
                    $tasks = Task::findMany(collect($taskListFrontEnd['tasks'])->pluck('id'));
                    $task_list->tasks()->delete();
                    $task_list->tasks()->saveMany($tasks);
                    $task_list->save();
                }
            }
        }

        return response()->json(TaskListResource::collection(TaskList::all()->load($this->relations)));
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
