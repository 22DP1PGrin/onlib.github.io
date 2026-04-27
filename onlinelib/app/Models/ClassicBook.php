<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Attēlo klasisko grāmatu
class ClassicBook extends Model
{
    // Datubāzes tabulas nosaukums
    protected $table = 'classic_book';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'name',
        'description',
        'age_limit',
        'Author_name',
        'Author_surname',
        'Year_publication',
    ];

    // Grāmatai var būt vairāki žanri
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'classic_book_id', 'genre_id');
    }

    // Grāmatai ir vairākas nodaļas
    public function chapters()
    {
        return $this->hasMany(BookChapter::class, 'classic_book_id');
    }

    // Grāmatai var būt vairākas vērtējumu atsauksmes
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'classic_book_id');
    }

    // Grāmatai var būt viena grāmatzīme vienam lietotājam
    public function bookmark()
    {
        return $this->hasOne(Bookmark::class, 'classic_book_id');
    }

    // Grāmatai var būt vairāki komentāri
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_book_id');
    }
}
