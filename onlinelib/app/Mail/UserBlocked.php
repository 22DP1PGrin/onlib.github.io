<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserBlocked extends Mailable
{
    use Queueable, SerializesModels;

    public $nickname;
    public $subject_pr;
    public $problem;
    public $blockedUntil;

    // Izveido jaunu vēstules instanci.
    public function __construct($nickname, $subject_pr, $problem, $blockedUntil)
    {
        $this->nickname = $nickname;
        $this->subject_pr = $subject_pr;
        $this->problem = $problem;
        $this->blockedUntil = $blockedUntil;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konta bloķēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.account_block', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'nickname' => $this->nickname,
                'subject_pr' => $this->subject_pr,
                'problem' => $this->problem,
                'blockedUntil' => $this->blockedUntil,
            ],
        );
    }
}
