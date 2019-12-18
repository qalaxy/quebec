<?php

namespace App\Mail;

use App\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorOriginatorNotification extends Mailable
{
    use Queueable, SerializesModels;

	public $error;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('w3.email.error-originator-notification')->subject('Error tracking');
    }
}
