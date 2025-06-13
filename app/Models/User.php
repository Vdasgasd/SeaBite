<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'role',
        'password',
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

    // Relasi ke reservasi
    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class, 'user_id', 'id');
    }


    public function pesanan(): HasMany
    {
        // Menyatakan bahwa seorang User bisa memiliki banyak Pesanan.
        return $this->hasMany(Pesanan::class, 'user_id', 'id');
    }

}
