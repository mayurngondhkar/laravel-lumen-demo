<?php

namespace App\Events;

use App\Todolist;
use Illuminate\Queue\SerializesModels;

class ToDoListCreatedEvent extends Event
{
    use SerializesModels;
    /**
     * @var Todolist
     */
    public $toDoList;

    /**
     * Create a new event instance.
     *
     * @param Todolist $toDoList
     */
    public function __construct(Todolist $toDoList)
    {
        $this->toDoList = $toDoList;
    }
}
