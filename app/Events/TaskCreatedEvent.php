<?php

namespace App\Events;

use App\Task;
use Illuminate\Queue\SerializesModels;

class TaskCreatedEvent extends Event
{
    use SerializesModels;
    /**
     * @var Task
     */
    public $task;

    /**
     * Create a new event instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
