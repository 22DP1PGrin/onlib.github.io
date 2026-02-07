<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserBookUnblocked extends Mailable
{
    use Queueable, SerializesModels;

    public $bookName;
    public $nickname;

    // Izveido jaunu vēstules instanci.
    public function __construct($bookName, $nickname)
    {
        $this->nickname = $nickname;
        $this->bookName = $bookName;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stāsta atbloķēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.user_unblock', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'bookName' => $this->bookName,
                'nickname' => $this->nickname,
            ],
        );
    }
}
