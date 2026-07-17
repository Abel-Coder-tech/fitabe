<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactConfirmation extends Mailable
{
    use Queueable;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre message - ' . config('app.name', 'FITAB'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-confirmation',
        );
    }
}
