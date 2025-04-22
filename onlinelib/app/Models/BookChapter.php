<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'name',
        'content',
        'order'
    ];

    // Attiecības ar grāmatu: katra nodaļa pieder konkrētai grāmatai
    public function book()
    {
        return $this->belongsTo(UserBook::class, 'book_id');
    }
}
