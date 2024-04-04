<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Token;

class UpdateCryptoData extends Command
{
    protected $signature = 'crypto:update';
    protected $description = 'Update cryptocurrency data from CoinGecko API';

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
            Token::updateOrInsert(
                ['crypto_id' => $crypto['id']], // Usar crypto_id para identificar de manera única cada criptomoneda
                [
                    'name' => $crypto['name'],
                    'symbol' => $crypto['symbol'],
                    'url' => $crypto['image'],
                    'total_supply' => $crypto['total_supply'], // Usar el total_supply directamente de la respuesta
                    'price' => $crypto['current_price'],
                    'user_id' => $user_id,
                ]
            );
        }

        $this->info('Cryptocurrency data updated successfully.');
    }
}
