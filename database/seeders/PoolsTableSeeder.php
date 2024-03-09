<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pool;
use App\Models\Token;

class PoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user to associate with the pool
        $token1 = Token::findOrFail(1);
        $token2 = Token::findOrFail(2);

        Pool::create([
            'name' => 'Pabloria',
            'description' => 'Mi primera chamba',
            'total_liquidity' =>  1000.00,
            'user_id' => $user->id,
            'token1_id' => $token1->id,
            'token2_id' => $token2->id,
        ]);
    }
}
