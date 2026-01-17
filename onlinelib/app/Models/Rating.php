<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'book_ratings';

    protected $fillable = [
        'grade',
        'user_id',
        'classic_book_id',
        'user_book_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classicBook(): BelongsTo
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    public function userBook(): BelongsTo
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }
}
