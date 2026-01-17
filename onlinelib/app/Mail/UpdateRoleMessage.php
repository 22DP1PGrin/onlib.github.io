<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpdateRoleMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Lietotājs

    // Izveido jaunu vēstules instanci.
    public function __construct($user)
    {
        $this->user = $user;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lomas atjaunināšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.updated_role', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'nickname' => $this->user->nickname, // Lietotājsvārds
                'role' => $this->user->role, // Jauna loma
            ],
        );
    }
}
