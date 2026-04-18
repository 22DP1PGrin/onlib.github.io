<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Attēlo lietotāju
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'nickname',
        'email',
        'password',
        'bio',
        'role',
        'is_blocked',
        'blocked_until',
        'avatar',
    ];

    // Slēptie lauki
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Lauku tipu pārveidošana
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked_until' => 'datetime',
        ];
    }

    // Lietotājam var būt vairākas grāmatas
    public function books()
    {
        return $this->hasMany(UserBook::class, 'user_id');
    }

    // Lietotājam var būt vairāki vērtējumi
    public function bookRatings()
    {
        return $this->hasMany(Rating::class);
    }

    // Lietotājam var būt vairāki komentāri
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
