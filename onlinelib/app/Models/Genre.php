<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Attēlo grāmatas žanru
class Genre extends Model
{
    // Masveidā aizpildāmais lauks
    protected $fillable = [
        'name'
    ];

    // Žanram var būt vairākas lietotāja grāmatas
    public function books()
    {
        return $this->belongsToMany(UserBook::class, 'user_book_genre', 'genre_id', 'book_id');
    }

    // Žanram var būt vairākas klasiskās grāmatas
    public function classic_books()
    {
        return $this->belongsToMany(ClassicBook::class, 'classic_book_genre', 'genre_id', 'book_id');
    }
}
