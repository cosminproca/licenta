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

class TaskListController extends Controller
{
    private array $relations = [
        'tasks',
        'tasks.subtasks',
        'tasks.assignee'
    ];

    public function __construct()
    {
        $this->authorizeResource('App\Models\Team,taskList', 'team,taskList');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team): JsonResponse
    {
        return response()->json(TaskListResource::collection(TaskList::all()->load($this->relations)->sortBy('order_column')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Team $team
     * @param StoreTaskListRequest $request
     * @return JsonResponse
     */
    public function store(Team $team, StoreTaskListRequest $request): JsonResponse
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
    public function show(Team $team, TaskList $taskList): JsonResponse
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
    public function update(Team $team, UpdateTaskListRequest $request, TaskList $taskList): JsonResponse
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
    public function destroy(Team $team, TaskList $taskList): JsonResponse
    {
        $taskList->tasks()->delete();

        return response()->json([
            'status' => $taskList->delete()
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
    public function updateAll(Team $team, UpdateAllTaskListRequest $request, TaskList $taskList): JsonResponse
    {
        $validated_data = $request->validated();

        $this->updateTaskListOrder($validated_data);

        return response()->json(TaskListResource::collection(TaskList::all()->load($this->relations)->sortBy('order_column')));
    }

    private function updateTaskListTasksOrder($taskListFrontEnd)
    {
        $tasks = Task::findMany(collect($taskListFrontEnd['tasks'])->pluck('id'));

        foreach ($tasks as $task) {
            $task->timestamps = false;

            foreach ($taskListFrontEnd['tasks'] as $taskFrontEnd) {
                if ($taskFrontEnd['id'] == $task->id) {
                    $task->update(['order_column' => $taskFrontEnd['order_column']]);
                    $task->save();
                }
            }
        }

        return $tasks;
    }

    private function updateTaskListOrder(array $validated_data)
    {
        $task_lists = TaskList::all()->load($this->relations);

        foreach ($task_lists as $task_list) {
            $task_list->timestamps = false;

            foreach ($validated_data['task_lists'] as $taskListFrontEnd) {
                if ($taskListFrontEnd['id'] == $task_list->id) {
                    $task_list->update(['order_column' => $taskListFrontEnd['order_column']]);
                    $task_list->save();

                    $tasks = $this->updateTaskListTasksOrder($taskListFrontEnd);

                    $task_list->tasks()->saveMany($tasks);
                    $task_list->save();
                    $task_list->refresh();
                }
            }
        }
    }
}
