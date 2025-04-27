<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassicBookChapter extends Model
{
    protected $fillable = [
        'book_id',
        'name',
        'content',
        'order'
    ];

    public function classic_book()
    {
        return $this->belongsTo(ClassicBook::class, 'book_id');
    }
}
