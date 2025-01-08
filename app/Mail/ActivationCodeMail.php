<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activationCode;
    public $user;

    public function __construct($activationCode, $user)
    {
        $this->activationCode = $activationCode;
        $this->user = $user;
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Your Activation Code')
            ->view('email.activation-code')
            ->with(['activationCode' => $this->activationCode, 'user' => $this->user]);
    }
}
