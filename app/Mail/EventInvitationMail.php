<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // <-- TAMBAHKAN BARIS INI
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

//           UBAH BAGIAN INI v v v v v v v v v
class EventInvitationMail extends Mailable implements ShouldQueue
{
    // TAMBAHKAN BARIS INI v v v
    use Queueable, SerializesModels;

    /**
     * The event instance.
     *
     * @var \App\Models\Event
     */
    public $event;

    /**
     * The participant instance.
     *
     * @var \App\Models\User
     */
    public $participant;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event, User $participant)
    {
        $this->event = $event;
        $this->participant = $participant;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Undangan Acara: ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.event-invitation',
            with: [
                'event' => $this->event,
                'participant' => $this->participant,
            ],
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
