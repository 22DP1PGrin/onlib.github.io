<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;  // Paroles atiestatīšanas kods, kas tiks nosūtīts e-pastā
    public User $user; // Lietotāju dati


    // Izveido jaunu vēstules instanci.
    public function __construct($code, $user)
    {
        $this->code = $code;
        $this->user = $user;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Paroles atiestatīšana.',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset_code', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'code' => $this->code,
                'nickname' => $this->user->nickname,
            ],
        );
    }
}
