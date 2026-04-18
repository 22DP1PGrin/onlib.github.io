<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Attēlo lietotāja grāmatas bloķēšanas informāciju
class StoryBlock extends Model
{
    // Datubāzes tabulas nosaukums
    protected $table = 'story_block';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'user_book_id',
        'subject',
        'problem',
    ];
}
