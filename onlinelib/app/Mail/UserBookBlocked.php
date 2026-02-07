<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserBookBlocked extends Mailable
{
    use Queueable, SerializesModels;

    public $bookName;
    public $nickname;
    public $subject;
    public $problem;

    // Izveido jaunu vēstules instanci.
    public function __construct($bookName, $nickname, $subject, $problem)
    {
        $this->nickname = $nickname;
        $this->bookName = $bookName;
        $this->subject = $subject;
        $this->problem = $problem;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stāsta bloķēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.user_block', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'bookName' => $this->bookName,
                'nickname' => $this->nickname,
                'subject' => $this->subject,
                'problem' => $this->problem,
            ],
        );
    }
}
