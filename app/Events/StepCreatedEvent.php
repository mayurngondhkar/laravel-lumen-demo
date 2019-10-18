<?php

namespace App\Events;

use App\Step;
use Illuminate\Queue\SerializesModels;

class StepCreatedEvent extends Event
{
    use SerializesModels;
    /**
     * @var Step
     */
    public $step;

    /**
     * Create a new event instance.
     *
     * @param Step $step
     */
    public function __construct(Step $step)
    {
        $this->step = $step;
    }
}
