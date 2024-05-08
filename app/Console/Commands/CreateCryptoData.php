<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Token;
use App\Models\Pool;

use App\Models\UserToken; 

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
        var_dump($cryptos);

        $user_id = 3; // ID del usuario al que se asignarán los tokens

        $createdTokens = [];

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
            $token->refresh();


            // Añadir el token al usuario con id 3 en la tabla user_tokens
            UserToken::create([
                'user_id' => $user_id,
                'token_id' => $token->id, // Utilizar directamente la propiedad id del modelo Token
                'amount' => $crypto['total_supply'], // Asumiendo que quieres asignar el total_supply como el amount
            ]);
            $createdTokens[] = $token;
        }

        // Create pools for each pair of tokens
        foreach ($createdTokens as $token) {
            foreach ($createdTokens as $otherToken) {
                if ($token->id !== $otherToken->id) {
                    $poolSupply = $token->total_supply * 0.01; // 10% del total supply
                    $poolName = $token->name . '-' . $otherToken->name; // Generar el nombre de la pool

                    // Calcular la cantidad equivalente de token2 basada en el precio de token1
                    $token1Amount = $poolSupply / $token->price;
                    // Calcular la cantidad equivalente de token1 basada en el precio de token2
                    $token2Amount = $poolSupply / $otherToken->price;

                    Pool::create([
                        'name' => $poolName, // Agregar el nombre de la pool
                        'description' => $poolName,
                        'token1_id' => $token->id,
                        'token2_id' => $otherToken->id,
                        'total_liquidity' => $poolSupply, // Establecer total_liquidity al mismo valor que supply
                        'token1_amount' => $token1Amount, // Usar la cantidad calculada
                        'token2_amount' => $token2Amount, // Usar la cantidad calculada
                        'user_id' => $user_id,
                    ]);
                }
            }
        }

        $this->info('Cryptocurrency data updated successfully.');
    }
}
