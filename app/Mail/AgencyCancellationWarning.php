<?php

namespace App\Mail;

use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgencyCancellationWarning extends Mailable
{
    use Queueable, SerializesModels;

    public $agency;
    public $warningMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Agency $agency, $warningMessage)
    {
        $this->agency = $agency;
        $this->warningMessage = $warningMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Attention: Avertissement d\'annulation - ' . $this->agency->agency_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.agency-cancellation-warning',
            with: [
                'agency' => $this->agency,
                'warningMessage' => $this->warningMessage,
                'remainingCancellations' => $this->agency->max_cancellations - $this->agency->cancellation_count
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