<?php

namespace App\Listeners;

use App\EmailLog;
use App\Events\TaskCreatedEvent;
use App\User;
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
     * @param  TaskCreatedEvent  $event
     * @return void
     */
    public function handle(TaskCreatedEvent $event)
    {
        $task = $event->task;

        $name = $task->getAttribute('name');
        $description = $task->getAttribute('description');
        $user_id = $task->getAttribute('user_id');

        $user = User::find($user_id);
        $sent_to = $user->email;

        $subject = "New task created";
        $body = "Task Name: $name, Task Description: $description";

        $email = new EmailLog([
            'sent_to' => $sent_to,
            'subject' => $subject,
            'body' => $body
        ]);

        $email->save();
    }
}
