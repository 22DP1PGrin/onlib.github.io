<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name'];

    // Attiecības ar grāmatām: viens žanrs var būt saistīts ar daudzām grāmatām
    public function books()
    {
        // Definē attiecības ar grāmatām, izmantojot starpposma tabulu 'book_genre'
        return $this->belongsToMany(UserBook::class, 'book_genre', 'genre_id', 'book_id');
    }

}
