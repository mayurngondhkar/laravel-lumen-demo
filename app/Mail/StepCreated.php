<?php

namespace App\Mail;

use App\Step;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StepCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $step;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Step $step)
    {
        $this->step = $step;
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
            ->view('dynamic_step_email_template')
            ->with('data', $this->step);
    }
}
