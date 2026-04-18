<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Attēlo lietotāja grāmatzīmi (saglabātu grāmatu ar noteiktu kategoriju)
class Bookmark extends Model
{
    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'user_id',
        'classic_book_id',
        'user_book_id',
        'bookmark_type_id'
    ];

    // Grāmatzīmes kategorija (tips)
    public function bookmarkType()
    {
        return $this->belongsTo(BookmarkType::class, 'bookmark_type_id');
    }

    // Saite uz klasisko grāmatu
    public function classicBook()
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    // Saite uz lietotāja grāmatu
    public function userBook()
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }

    // Lietotājs, kuram pieder grāmatzīme
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
