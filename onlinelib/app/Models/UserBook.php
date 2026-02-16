<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;

class UserBook extends Model
{
    use HasFactory;

    // Aizpildāmās vērtības, kas var tikt masveidā pievienotas
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'age_limit',
    ];

    // Attiecības ar lietotāju: katram grāmatai ir viens lietotājs
    public function user()
    {
        return $this->belongsTo(User::class); // Grāmatai pieder lietotājam
    }

    // Attiecības ar nodaļām: katrai grāmatai var būt daudzas nodaļas
    public function chapters()
    {
        return $this->hasMany(BookChapter::class, 'user_book_id'); // Grāmatai ir vairākas nodaļas
    }

    // Attiecības ar žanriem: grāmata var piederēt vairākiem žanriem
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'user_book_id', 'genre_id'); // Grāmatai var būt vairāki žanri
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_book_id');
    }
    public function bookmark()
    {
        return $this->hasOne(Bookmark::class, 'user_book_id')
            ->with('bookmarkType');
    }

    public function block()
    {
        return $this->hasOne(StoryBlock::class, 'user_book_id', 'id');
    }
}
