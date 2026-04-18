<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Attēlo grāmatas nodaļu
class BookChapter extends Model
{
    // Datubāzes tabulas nosaukums
    protected $table = 'book_chapters';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'classic_book_id',
        'user_book_id',
        'name',
        'content',
        'order',
    ];

    // Nodaļa pieder klasiskajai grāmatai
    public function classicBook(): BelongsTo
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    // Nodaļa pieder lietotāja grāmatai
    public function userBook(): BelongsTo
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }
}
