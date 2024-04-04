<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Token;
use App\Models\UserToken; // Asegúrate de importar el modelo UserToken

class CreateCryptoData extends Command
{
    protected $signature = 'crypto:create';
    protected $description = 'Create cryptocurrency data from CoinGecko API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $response = Http::get('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1&sparkline=false');
        $cryptos = $response->json();

        $user_id = 3; // ID del usuario al que se asignarán los tokens

        foreach ($cryptos as $crypto) {
            // Insertar los datos en la tabla tokens
            $token = Token::firstOrCreate(
                ['crypto_id' => $crypto['id']], // Usar crypto_id para identificar de manera única cada criptomoneda
                [
                    'name' => $crypto['name'],
                    'symbol' => $crypto['symbol'],
                    'url' => $crypto['image'],
                    'total_supply' => $crypto['total_supply'],
                    'price' => $crypto['current_price'],
                    'user_id' => $user_id,
                ]
            );

            // Añadir el token al usuario con id 3 en la tabla user_tokens
            UserToken::create([
                'user_id' => $user_id,
                'token_id' => $token->id, // Utilizar directamente la propiedad id del modelo Token
                'amount' => $crypto['total_supply'], // Asumiendo que quieres asignar el total_supply como el amount
            ]);
        }

        $this->info('Cryptocurrency data updated successfully.');
    }
}