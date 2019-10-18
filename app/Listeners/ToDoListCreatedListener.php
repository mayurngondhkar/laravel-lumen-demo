<?php

namespace App\Listeners;

use App\EmailLog;
use App\Events\ToDoListCreatedEvent;
use App\Mail\ToDoListCreated;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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

        $name = $toDoList->getAttribute('name');
        $description = $toDoList->getAttribute('description');
        $user_id = $toDoList->getAttribute('user_id');

        $user = User::find($user_id);
        $sent_to = $user->email;

        $subject = "New todo list created";
        $body = "Todo List Name: $name, Todo List Description: $description";

        $email = new EmailLog([
            'sent_to' => $sent_to,
            'subject' => $subject,
            'body' => $body
        ]);

        $email->save();

        Mail::to($user)->send(new ToDoListCreated($toDoList));

    }
}
