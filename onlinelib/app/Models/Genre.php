<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name'];

    // Attiecības ar grāmatām: viens žanrs var būt saistīts ar daudzām grāmatām
    public function books()
    {
        // Definē attiecības ar grāmatām, izmantojot starpposma tabulu 'user_book_genre'
        return $this->belongsToMany(UserBook::class, 'user_book_genre', 'genre_id', 'book_id');
    }

    public function classic_books()
    {
        // Definē attiecības ar grāmatām, izmantojot starpposma tabulu 'user_book_genre'
        return $this->belongsToMany(ClassicBook::class, 'classic_book_genre', 'genre_id', 'book_id');
    }

}
