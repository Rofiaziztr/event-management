<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;
    public $participant;

    public function __construct(Event $event, User $participant)
    {
        $this->event = $event;
        $this->participant = $participant;
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