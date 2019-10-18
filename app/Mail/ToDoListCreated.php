<?php

namespace App\Mail;

use App\Todolist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToDoListCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $toDoList;

    /**
     * Create a new message instance.
     *
     * @param Todolist $todolist
     */
    public function __construct(Todolist $todolist)
    {
        $this->toDoList = $todolist;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mail@example.com', 'Mailtrap')
            ->subject('Mailtrap Confirmation')
            ->view('dynamic_toDoList_email_template')
            ->with('data', $this->toDoList);
    }
}
