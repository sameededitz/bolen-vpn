<?php

namespace App\Mail;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserActivationCode extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $activationCode;
    public User $user;
    public Plan $plan;

    /**
     * Create a new message instance.
     */
    public function __construct(string $activationCode, User $user, Plan $plan)
    {
        $this->activationCode = $activationCode;
        $this->user = $user;
        $this->plan = $plan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Purchase Code for ' . ($this->plan->name ?? 'Plan'),
            to: $this->user->email,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.code',
            with: [
                'code' => $this->activationCode,
                'user' => $this->user,
                'plan' => $this->plan,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
