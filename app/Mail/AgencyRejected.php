<?php

namespace App\Mail;

use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgencyRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $agency;

    public function __construct(Agency $agency)
    {
        $this->agency = $agency;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Demande d\'Inscription Rejetée - ' . $this->agency->agency_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.agency.rejected',
        );
    }
} 