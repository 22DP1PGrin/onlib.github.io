<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookChapter extends Model
{
    protected $table = 'book_chapters';

    protected $fillable = [
        'classic_book_id',
        'user_book_id',
        'name',
        'content',
        'order',
    ];

    public function classicBook(): BelongsTo
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    public function userBook(): BelongsTo
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }
}
