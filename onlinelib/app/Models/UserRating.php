<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    use HasFactory;

    protected $table = 'User_books_ratings';

    protected $fillable = [
        'grade',
        'user_id',
        'book_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить книгу, к которой относится оценка
     */
    public function book()
    {
        return $this->belongsTo(UserBook::class, 'book_id');
    }
}
