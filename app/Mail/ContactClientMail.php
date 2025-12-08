<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detalii;

    /**
    * Constructorul clasei Mailable.
    * Primeste datele din controller si le atribuie atributului public $detalii.
    *
    * @param array $detalii Datele necesare pentru construirea emailului
    */
    public function __construct($detalii)
    {
        $this->detalii = $detalii;
    }

    /**
    * Configureaza continutul emailului.
    * Emailul pleaca de pe server, setam header-ul 'Reply-To'
    * catre adresa de email a consilierului logat.
    *
    * @return \Illuminate\Mail\Mailables\Envelope
    */
    public function envelope(): Envelope
    {  
        return new Envelope(
            subject: $this->detalii['subiect'],
            replyTo: [
                new Address($this->detalii['email_consilier'], $this->detalii['nume_consilier']),
            ],
        );
    }

    /**
    * Defineste continutul emailului.
    * Specifica fisierul Blade care va randa corpul mesajului.
    *
    * @return \Illuminate\Mail\Mailables\Content
    */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_client',
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
