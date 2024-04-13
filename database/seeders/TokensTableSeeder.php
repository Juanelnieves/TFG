<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Http;
use App\Services\CryptoPriceService;

class TokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user to associate with the token
        $defaultIcon = 'https://cdn-icons-png.freepik.com/512/5266/5266579.png';
        $cryptoPrice = CryptoPriceService::getCryptoPrice('dogecoin');

        Token::create([
            'name' => 'Pedrito',
            'symbol' => 'PDT',
            'url' => $defaultIcon,
            'total_supply' =>  1000000,
            'user_id' => $user->id,
            'crypto_id' => 'prueba1',
            'price' =>  $cryptoPrice * 2,

        ]);
        Token::create([
            'name' => 'TPD',
            'symbol' => 'TPD',
            'url' => $defaultIcon,
            'total_supply' =>  1000000,
            'user_id' => $user->id,
            'crypto_id' => 'prueba2',
            'price' =>  $cryptoPrice * 2.1,

        ]);
        Token::create([
            'name' => 'KENTUCKY',
            'symbol' => 'KFC',
            'url' => $defaultIcon,
            'total_supply' =>  1000000,
            'user_id' => $user->id,
            'crypto_id' => 'prueba3',
            'price' =>  $cryptoPrice * 2.4,

        ]);

        // Token de test del User 2
        Token::create([
            'name' => 'Token C',
            'symbol' => 'TKC',
            'url' => $defaultIcon,
            'total_supply' => 1000000,
            'user_id' => 2,
            'crypto_id' => 'prueba4',
            'price' =>  $cryptoPrice * 1.2,

        ]);
    }
}
