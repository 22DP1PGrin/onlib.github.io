<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Attēlo lietotāja vērtējumu
class Rating extends Model
{
    use HasFactory;

    // Datubāzes tabulas nosaukums
    protected $table = 'book_ratings';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'grade',
        'user_id',
        'classic_book_id',
        'user_book_id',
    ];

    // Saite uz lietotāju
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Saite uz klasisko grāmatu
    public function classicBook(): BelongsTo
    {
        return $this->belongsTo(ClassicBook::class, 'classic_book_id');
    }

    // Saite uz lietotāja grāmatu
    public function userBook(): BelongsTo
    {
        return $this->belongsTo(UserBook::class, 'user_book_id');
    }
}
