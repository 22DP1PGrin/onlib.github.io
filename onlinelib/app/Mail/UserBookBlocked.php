<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserBookBlocked extends Mailable
{
    use Queueable, SerializesModels;

    public $bookName; // Stāsta nosaukums
    public $nickname; // Lietotājvārds
    public $subject_pr; // Bloķēšanas iemesls
    public $problem; // Bloķēšanas laiks

    // Izveido jaunu vēstules instanci
    public function __construct($bookName, $nickname, $subject_pr, $problem)
    {
        $this->nickname = $nickname;
        $this->bookName = $bookName;
        $this->subject_pr = $subject_pr;
        $this->problem = $problem;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stāsta bloķēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā
    public function content(): Content
    {

        return new Content(
            view: 'emails.user_block', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'bookName' => $this->bookName,
                'nickname' => $this->nickname,
                'subject_pr' => $this->subject_pr,
                'problem' => $this->problem,
            ],
        );
    }
}
