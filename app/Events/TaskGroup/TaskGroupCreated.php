<?php

namespace App\Events\TaskGroup;

use App\Models\TaskGroup;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskGroupCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TaskGroup $taskGroup;

    /**
     * Create a new event instance.
     */
    public function __construct(TaskGroup $taskGroup)
    {
        $this->taskGroup = $taskGroup;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("App.Models.Project.{$this->taskGroup->project_id}"),
        ];
    }
}
