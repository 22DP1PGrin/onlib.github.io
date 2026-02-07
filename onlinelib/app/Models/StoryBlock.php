<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryBlock extends Model
{
    protected $table = 'story_block';

    protected $fillable = [
        'user_book_id',
        'subject',
        'problem',
    ];
}
