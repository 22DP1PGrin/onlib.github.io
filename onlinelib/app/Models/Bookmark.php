<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'classic_book_id', 'user_book_id', 'bookmark_type_id'];

    public function bookmarkType()
    {
        return $this->belongsTo(BookmarkType::class, 'bookmark_type_id');
    }

    public function classicBook()
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    public function userBook()
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
