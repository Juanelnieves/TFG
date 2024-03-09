<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidity extends Model
{
    use HasFactory;
    protected $table = 'liquiditys';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pool()
    {
        return $this->belongsTo(Pool::class);
    }
    public function token()
    {
        return $this->belongsTo(Token::class);
    }
}
