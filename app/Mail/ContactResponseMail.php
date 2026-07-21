<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactResponseMail extends Mailable
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $name,
        public string $replyMessage,
        public string $originalSubject = '',
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->originalSubject
            ? 'Re: ' . $this->originalSubject
            : 'Réponse de FITAB';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.contact-response',
        );
    }
}
