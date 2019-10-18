<?php

namespace App\Listeners;

use App\Events\TaskCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  STaskCreatedEvent  $event
     * @return void
     */
    public function handle(TaskCreatedEvent $event)
    {
        $task = $event->task;
    }
}
