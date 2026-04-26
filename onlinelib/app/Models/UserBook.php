<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Attēlo lietotāja izveidotu grāmatu
class UserBook extends Model
{
    use HasFactory;

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'age_limit',
    ];

    // Saite uz lietotāju
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Grāmatai var būt vairākas nodaļas
    public function chapters()
    {
        return $this->hasMany(BookChapter::class, 'user_book_id');
    }

    // Grāmatai var būt vairāki žanri
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'user_book_id', 'genre_id');
    }

    // Grāmatai var būt vairāki vērtējumi
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_book_id');
    }

    // Katram lietotājam grāmatai var būt viena grāmatzīme
    public function bookmark()
    {
        return $this->hasOne(Bookmark::class, 'user_book_id');
    }

    // Grāmatai var būt viens bloķēšanas ieraksts
    public function block()
    {
        return $this->hasOne(StoryBlock::class, 'user_book_id', 'id');
    }

    // Grāmatai var būt vairāki komentāri
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_book_id');
    }
}
