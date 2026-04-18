<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Modelis, kas attēlo grāmatzīmju tipu
class BookmarkType extends Model
{
    use HasFactory;

    // Lauki, kurus drīkst masveidā aizpildīt
    protected $fillable = [
            'name'
        ];

    // Norāda, ka tabulā netiek izmantoti created_at un updated_at lauki
    public $timestamps = false;

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
