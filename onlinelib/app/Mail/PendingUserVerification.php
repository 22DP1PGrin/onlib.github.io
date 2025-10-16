<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingUserVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $pending;

    public function __construct($pending)
    {
        $this->pending = $pending;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-pasta verifikācija',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pending_user_verification',
            with: [
                'nickname' => $this->pending['nickname'],
                'token' => $this->pending['token'],
            ],
        );
    }


}
