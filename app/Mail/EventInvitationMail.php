<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $participant;
    public $password;

    public function __construct($event, $participant, $password = null)
    {
        $this->event = $event;
        $this->participant = $participant;
        $this->password = $password;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Undangan Acara: ' . $this->event->title,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.event-invitation',
        );
    }

    public function attachments()
    {
        return [];
    }
}