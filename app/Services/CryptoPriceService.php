<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CryptoPriceService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.coingecko.com/api/v3/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-cg-demo-api-key' => 'CG-WxApqcxBfdXfXE78vRTM5zMx', //API KEY
            ],
        ]);
    }

    public function getTopCryptos()
    {
        $attempts = 0;
        $maxAttempts = 5;
        $waitTime = 10; // Tiempo de espera en segundos

        while ($attempts < $maxAttempts) {
            try {
                $response = $this->client->get('coins/markets', [
                    'query' => [
                        'vs_currency' => 'usd',
                        'order' => 'market_cap_desc',
                        'per_page' => 10,
                        'page' => 1,
                        'sparkline' => false,
                    ],
                ]);

                return json_decode($response->getBody(), true);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                if ($e->getResponse()->getStatusCode() == 429) {
                    $attempts++;
                    sleep($waitTime);
                } else {
                    throw $e;
                }
            }
        }

        throw new \Exception('No se pudo obtener los datos después de varios intentos.');
    }

    public function get24hVolume()
    {
        try {
            $response = $this->client->get('coins/markets', [
                'query' => [
                    'vs_currency' => 'usd',
                    'ids' => 'bitcoin', 
                    'order' => 'market_cap_desc',
                    'per_page' => 1,
                    'page' => 1,
                    'sparkline' => false,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            // Acceder al volumen de las últimas 24 horas de BTC
            $volume = $data[0]['total_volume'];

            // Convertir el volumen a float para asegurarse de que es un número
            $volume = floatval($volume);

            // Verificar que $volume es un número antes de dividirlo
            if (is_numeric($volume)) {
                // Formatear el volumen a millones
                $formattedVolume = number_format($volume / 1000000, 2);
                return $formattedVolume;
            } else {
                // Manejar el caso en que $volume no es un número
                return '45147';
            }
        } catch (\Exception $e) {
            // Manejar la excepción
            return '45147';
        }
    }

    public function getBTCMarketCap()
{
    try {
        $response = $this->client->get("coins/bitcoin", [
            'query' => [
                'vs_currency' => 'usd',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $marketCap = $data['market_data']['market_cap']['usd'];

        // Convertir el market cap a float para asegurarse de que es un número
        $marketCap = floatval($marketCap);

        // Verificar que $marketCap es un número antes de dividirlo
        if (is_numeric($marketCap)) {
            // Formatear el market cap a millones
            $formattedMarketCap = number_format($marketCap / 1000000, 2);
            return $formattedMarketCap;
        } else {
            // Manejar el caso en que $marketCap no es un número
            return '184310270';
        }
    } catch (\Exception $e) {
        // Manejar la excepción
        return '8384310270';
    }
}

    public function getTokenPrice($tokenId)
    {
        try {
            $response = $this->client->get("coins/{$tokenId}", [
                'query' => [
                    'vs_currency' => 'usd',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $price = $data['market_data']['current_price']['usd'];

            return $price;
        } catch (\Exception $e) {
            // Manejar la excepción
            return '10';
        }
    }

    public function updateCryptoData()
    {
        $response = Http::get('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1&sparkline=false');
        $cryptos = $response->json();

        foreach ($cryptos as $crypto) {
            // Aquí asumimos que tienes una tabla en tu base de datos para almacenar los datos de las criptomonedas
            // y que esta tabla tiene columnas para 'id', 'name', 'symbol', 'market_cap', 'current_price', y 'image'
            DB::table('cryptos')->updateOrInsert(
                ['id' => $crypto['id']],
                [
                    'name' => $crypto['name'],
                    'symbol' => $crypto['symbol'],
                    'market_cap' => $crypto['market_cap'],
                    'current_price' => $crypto['current_price'],
                    'image' => $crypto['image'],
                ]
            );
        }
    }

}
