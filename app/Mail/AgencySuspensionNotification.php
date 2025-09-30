<?php

namespace App\Mail;

use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgencySuspensionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $agency;

    /**
     * Create a new message instance.
     */
    public function __construct(Agency $agency)
    {
        $this->agency = $agency;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚫 Suspension de compte - ' . $this->agency->agency_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.agency-suspension-notification',
            with: [
                'agency' => $this->agency,
                'suspensionReason' => $this->agency->suspension_reason,
                'suspendedAt' => $this->agency->suspended_at
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