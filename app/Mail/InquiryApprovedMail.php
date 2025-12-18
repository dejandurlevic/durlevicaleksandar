<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $plan;
    public $registrationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $plan, $registrationUrl)
    {
        $this->name = $name;
        $this->plan = $plan;
        $this->registrationUrl = $registrationUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Registration Invitation - FitCoachAleksandar',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-approved',
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
