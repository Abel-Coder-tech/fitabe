<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewsletterConfirmation extends Mailable
{
    use Queueable;

    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation d\'abonnement - ' . config('app.name', 'FITAB'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter-confirmation',
        );
    }
}
