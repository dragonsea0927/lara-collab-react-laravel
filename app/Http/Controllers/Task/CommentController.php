<?php

namespace App\Http\Controllers\Task;

use App\Events\CommentCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Project;
use App\Models\Task;

class CommentController extends Controller
{
    public function index(Project $project, Task $task)
    {
        $this->authorize('viewAny', [Task::class, $project]);

        return response()->json(
            $task->comments()->with(['user:id,name,avatar,job_title'])->latest()->get(),
        );
    }

    public function store(StoreCommentRequest $request, Project $project, Task $task)
    {
        $this->authorize('create', [Task::class, $project]);

        $comment = $task->comments()->create(
            $request->validated() + ['user_id' => auth()->id()]
        );

        CommentCreated::dispatch($comment);

        return response()->json(['comment' => $comment->load(['user:id,name,avatar,job_title'])]);
    }
}