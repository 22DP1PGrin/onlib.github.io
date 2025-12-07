<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingUserVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $pending; // Pagaidu sesija ar datiem

    // Izveido jaunu vēstules instanci.
    public function __construct($pending)
    {
        $this->pending = $pending;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-pasta verifikācija.',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {
        $verificationUrl = isset($this->pending['user_id'])
            ? url('/email-change/' . $this->pending['token'])  // pasta maiņai
            : url('/verify-pending/' . $this->pending['token']); // registrācijai

        return new Content(
            view: 'emails.pending_user_verification', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'nickname' => $this->pending['nickname'],
                'verificationUrl' => $verificationUrl,
            ],
        );
    }


}
