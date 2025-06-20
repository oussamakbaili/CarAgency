<?php

namespace App\Mail;

use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgencyPermanentlyRejected extends Mailable
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
            subject: 'Compte Agence Définitivement Rejeté - ' . $this->agency->agency_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.agency.permanently-rejected',
        );
    }
} 