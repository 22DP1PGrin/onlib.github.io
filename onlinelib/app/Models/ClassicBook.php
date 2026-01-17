<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassicBook extends Model
{
    protected $table = 'classic_book';

    protected $fillable = [
        'name',
        'description',
        'age_limit',
        'Author_name',
        'Author_surname',
        'Year_publication',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'classic_book_id', 'genre_id'); // Grāmatai var būt vairāki žanri
    }

    public function chapters()
    {
        return $this->hasMany(BookChapter::class, 'classic_book_id'); // Grāmatai ir vairākas nodaļas
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'classic_book_id');
    }

    public function bookmark()
    {
        return $this->hasOne(Bookmark::class, 'classic_book_id')
            ->where('user_id', auth()->id())
            ->with('bookmarkType');
    }
}
