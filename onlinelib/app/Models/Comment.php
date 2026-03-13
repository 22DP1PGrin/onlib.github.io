<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'content',
        'user_id',
        'user_book_id',
        'classic_book_id',
        'comment_parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_parent_id');
    }

    public function userBook()
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }

    public function classicBook()
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }
}
