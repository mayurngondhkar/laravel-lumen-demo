<?php

namespace App\Listeners;

use App\EmailLog;
use App\Events\StepCreatedEvent;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class StepCreatedListener implements ShouldQueue
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
     * @param  StepCreatedEvent  $event
     * @return void
     */
    public function handle(StepCreatedEvent $event)
    {
        $step = $event->step;

        $name = $step->getAttribute('name');
        $description = $step->getAttribute('description');
        $user_id = $step->getAttribute('user_id');

        $user = User::find($user_id);
        $sent_to = $user->email;

        $subject = "New step created";
        $body = "Step Name: $name, Step Description: $description";

        $email = new EmailLog([
            'sent_to' => $sent_to,
            'subject' => $subject,
            'body' => $body
        ]);

        $email->save();
    }
}
