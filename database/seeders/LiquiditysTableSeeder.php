<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pool;
use App\Models\Token;
use App\Models\Liquidity;

class LiquiditysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user to associate with the liquidity
        $pool = Pool::first(); // Get the first pool to associate with the liquidity
        $token = Token::first(); // Get the first token to associate with the liquidity

        Liquidity::create([
            'amount' =>  500.00,
            'user_id' => $user->id,
            'pool_id' => $pool->id,
            'token_id' => $token->id,
        ]);
    }
}
