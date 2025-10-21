<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordMessage extends Mailable
{
    use Queueable, SerializesModels;

    public User $user; // Lietotāju dati

    // Izveido jaunu vēstules instanci.
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Paroles maiņa.',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_change', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'nickname' => $this->user->nickname,
                'email' => $this->user->email,
            ],
        );
    }


}
