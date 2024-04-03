<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Token;

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
        $defaultIcon= 'https://cdn-icons-png.freepik.com/512/5266/5266579.png';

        Token::create([
            'name' => 'Pedrito',
            'symbol' => 'PDT',
            'url' => 'https://www.buscabiografias.com/img/people/San-Pedro-Ap%C3%B3stol.jpg',
            'total_supply' =>  1000000,
            'user_id' => $user->id,
        ]);
        Token::create([
            'name' => 'TPD',
            'symbol' => 'TPD',
            'url' => 'https://upload.wikimedia.org/wikipedia/en/b/b9/Solana_logo.png',
            'total_supply' =>  1000000,
            'user_id' => $user->id,
        ]);
        Token::create([
            'name' => 'KENTUCKY',
            'symbol' => 'KFC',
            'url' => 'https://media.licdn.com/dms/image/C4D03AQEoGN9yZGXaXA/profile-displayphoto-shrink_400_400/0/1663705760851?e=2147483647&v=beta&t=m0krYXwfi2k-ialrvr26Jw-qqYG24EJrJbOIohBfmqA',
            'total_supply' =>  1000000,
            'user_id' => $user->id,
        ]);

         // Token de test del User 2
         Token::create([
            'name' => 'Token C',
            'symbol' => 'TKC',
            'url' => '',
            'total_supply' => 1000000,
            'user_id' => 2, 
        ]);
    }
}
