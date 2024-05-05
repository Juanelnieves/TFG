<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'total_liquidity',
        'token1_id',
        'token2_id',
        'token1_amount', 
        'token2_amount',
        'total_liquidity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liquiditys()
    {
        return $this->hasMany(Liquidity::class,  "user_id");
    }

    public function token1()
    {
        return $this->belongsTo(Token::class, 'token1_id');
    }

    public function token2()
    {
        return $this->belongsTo(Token::class, 'token2_id');
    }
}
