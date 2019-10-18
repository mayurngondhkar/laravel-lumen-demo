<?php

namespace App\Listeners;

use App\Events\ToDoListCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToDoListCreatedListener implements ShouldQueue
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
     * @param  ToDoListCreatedEvent  $event
     * @return void
     */
    public function handle(ToDoListCreatedEvent $event)
    {
        $toDoList = $event->toDoList;
    }
}
