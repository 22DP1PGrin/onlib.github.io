<?php

namespace App\Mail;

use App\Models\UserBook;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $book;

    // Izveido jaunu vēstules instanci.
    public function __construct(UserBook $book)
    {
        $this->book = $book;
    }

    // E-pasta vēstules tēma.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stāsta dzēšana',
        );
    }

    // Satura veidu un datus, kas tiks izmantoti e-pastā.
    public function content(): Content
    {

        return new Content(
            view: 'emails.book_delete', // Blade skats, kas tiek nosūtīts kā e-pasts
            with: [
                'title' => $this->book->name,
                'nickname' => $this->book->user->nickname,
            ],
        );
    }
}
