<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'symbol',
        'total_supply',
        'price',
        'url',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_tokens')->withPivot('amount');
    }

    public function userTokens()
    {
        return $this->hasMany(UserToken::class);
    }

    public function liquiditys()
    {
        return $this->hasMany(Liquidity::class,  "user_id");
    }
}
