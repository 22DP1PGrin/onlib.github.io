<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserUnblocked extends Mailable
{
    use Queueable, SerializesModels;

    public $nickname;

    // Izveido jaunu vēstules instanci.
    public function __construct($nickname)
    {
        $this->nickname = $nickname;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konta atbloķēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.account_unblock', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'nickname' => $this->nickname,
            ],
        );
    }
}
