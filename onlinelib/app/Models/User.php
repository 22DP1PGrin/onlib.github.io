<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'nickname',
        'email',
        'password',
        'bio'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function books()
    {
        return $this->hasMany(UserBook::class, 'user_id'); // Lietotājam pieder daudzas grāmatas
    }

    public function bookRatings()
    {
        return $this->hasMany(UserRating::class);
    }

    public function classicBookRatings()
    {
        return $this->hasMany(ClassicRating::class);
    }
}
