<?php

namespace App\Services;

use GuzzleHttp\Client;

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
}
