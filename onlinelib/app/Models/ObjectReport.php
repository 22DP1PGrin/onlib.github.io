<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectReport extends Model
{
    protected $table = 'object_report';

    protected $fillable = [
        'subject',
        'problem',
        'reporter_user_id',
        'user_book_id',
        'classic_book_id',
        'reported_user_id',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    public function userBook()
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }

    public function classicBook()
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
