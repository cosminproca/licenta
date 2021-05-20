<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private $relations = [
        'user',
        'task'
    ];

    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment,team');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function index(Team $team)
    {
        return response()->json(CommentResource::collection(Comment::all()->load($this->relations)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Team $team
     * @param StoreCommentRequest $request
     * @return JsonResponse
     */
    public function store(Team $team, StoreCommentRequest $request)
    {
        $validated_data = $request->validated();
        $comment = Comment::create($validated_data);

        $status = $comment->save();

        return response()->json([
            'status' => $status,
            'data' => new CommentResource($comment->load($this->relations))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @param Comment $comment
     * @return JsonResponse
     */
    public function show(Team $team, Comment $comment)
    {
        return response()->json(new CommentResource($comment->load($this->relations)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Team $team
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(Team $team, UpdateCommentRequest $request, Comment $comment)
    {
        $validated_data = $request->validated();

        $status = $comment->update($validated_data);

        return response()->json([
            'status' => $status,
            'data' => new CommentResource($comment->load($this->relations))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Team $team, Comment $comment)
    {
        return response()->json([
            'status' => $comment->delete()
        ]);
    }
}
