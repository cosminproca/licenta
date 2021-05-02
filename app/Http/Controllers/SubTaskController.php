<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubTaskRequest;
use App\Http\Requests\UpdateSubTaskRequest;
use App\Http\Resources\SubTaskResource;
use App\Models\SubTask;
use Illuminate\Http\JsonResponse;

class SubTaskController extends Controller
{
    private $relations = [
        'task',
        'assignee'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(SubTaskResource::collection(SubTask::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreSubTaskRequest $request)
    {
        $validated_data = $request->validated();
        $taskList = SubTask::create($validated_data);

        $status = $taskList->save();

        return response()->json([
            'status' => $status,
            'data' => new SubTaskResource($taskList->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param SubTask $subTask
     * @return JsonResponse
     */
    public function show(SubTask $subTask)
    {
        return response()->json(new SubTaskResource($subTask->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubTaskRequest $request
     * @param SubTask $subTask
     * @return JsonResponse
     */
    public function update(UpdateSubTaskRequest $request, SubTask $subTask)
    {
        $validated_data = $request->validated();

        $status = $subTask->update($validated_data);

        return response()->json([
            'status' => $status,
            'data' => new SubTaskResource($subTask->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SubTask $subTask
     * @return JsonResponse
     */
    public function destroy(SubTask $subTask)
    {
        return response()->json([
            'status' => $subTask->delete()
        ]);
    }
}
