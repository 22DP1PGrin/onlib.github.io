<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Attēlo komentāru (var būt gan pie grāmatas, gan lietotāja grāmatas)
class Comment extends Model
{
    // Datubāzes tabulas nosaukums
    protected $table = 'comments';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'content',
        'user_id',
        'user_book_id',
        'classic_book_id',
        'comment_parent_id'
    ];

    // Saite uz lietotāju
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Saite uz komentāra atbildi
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_parent_id');
    }

    // Saite uz lietotāja grāmatu
    public function userBook()
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }

    // Saite uz klasisko grāmatu
    public function classicBook()
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }
}
