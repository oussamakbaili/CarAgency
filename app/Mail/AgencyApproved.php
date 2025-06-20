<?php

namespace App\Mail;

use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgencyApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $agency;

    public function __construct(Agency $agency)
    {
        $this->agency = $agency;
    }

    public function build()
    {
        return $this->markdown('emails.agency.approved')
                    ->subject('Votre compte agence a été approuvé');
    }
} 