<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Attēlo lietotāja ziņojumu
class ObjectReport extends Model
{
    // Datubāzes tabulas nosaukums
    protected $table = 'object_report';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'subject',
        'problem',
        'reporter_user_id',
        'user_book_id',
        'classic_book_id',
        'reported_user_id',
        'comment_id',
    ];

    // Saite uz lietotāju, kurš izveidoja ziņojumu
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    // Saite uz lietotāja grāmatu
    public function userBook()
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }

    // Saite uz klasisko grāmatu
    public function classicBook()
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    // Saite uz lietotāju(ziņotais lietotājs)
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    // Saite uz komentāru
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
