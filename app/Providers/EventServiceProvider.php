<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ExampleEvent' => [
            'App\Listeners\ExampleListener',
        ],
        'App\Events\TaskCreatedEvent' => [
            'App\Listeners\TaskCreatedListener',
        ],
        'App\Events\StepCreatedEvent' => [
            'App\Listeners\StepCreatedListener',
        ],
        'App\Events\ToDoListCreatedEvent' => [
            'App\Listeners\ToDoListCreatedListener',
        ],
    ];
}
