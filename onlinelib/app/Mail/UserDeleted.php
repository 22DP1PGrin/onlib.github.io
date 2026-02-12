<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    // Izveido jaunu vēstules instanci.
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konta dzēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.account_delete', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'nickname' => $this->user->nickname,
            ],
        );
    }
}
