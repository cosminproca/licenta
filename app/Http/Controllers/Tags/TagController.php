<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TagResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Tags\Tag;

class TagController extends Controller
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
        return response()->json(TagResource::collection(Tag::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTagRequest $request
     * @return JsonResponse
     */
    public function store(StoreTagRequest $request)
    {
        $validated_data = $request->validated();
        $tag = Tag::create($validated_data);

        $status = $tag->save();

        return response()->json([
            'status' => $status,
            'data' => new TagResource($tag->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function show(Tag $tag)
    {
        return response()->json(new TagResource($tag->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @return JsonResponse
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validated_data = $request->validated();

        $status = $tag->update($validated_data);

        return response()->json([
            'status' => $status,
            'data' => new TagResource($tag->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag)
    {
        return response()->json([
            'status' => $tag->delete()
        ]);
    }
}
