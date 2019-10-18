<?php

namespace App\Listeners;

use App\Events\StepCreatedEvent;
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
    }
}
