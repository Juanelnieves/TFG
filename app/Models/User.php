<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pools(){
        return $this->hasMany(Pool::class,  "user_id");
    }
    public function tokens(){
        return $this->hasMany(Token::class,  "user_id");
    }
    public function transactions(){
        return $this->hasMany(Transaction::class,  "user_id");
    }
    public function stakings(){
        return $this->hasMany(Staking::class,  "user_id");
    }
    public function liquiditys(){
        return $this->hasMany(Liquidity::class,  "user_id");
    }

}
