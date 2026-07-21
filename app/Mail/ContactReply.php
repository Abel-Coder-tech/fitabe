<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactReply extends Mailable
{
    use Queueable;

    public Contact $contact;
    public string $reponse;

    public function __construct(Contact $contact, string $reponse)
    {
        $this->contact = $contact;
        $this->reponse = $reponse;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réponse à votre message - ' . config('app.name', 'FITAB'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-reply',
        );
    }
}
