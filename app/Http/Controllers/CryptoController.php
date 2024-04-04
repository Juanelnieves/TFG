<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CryptoPriceService;

class CryptoController extends Controller
{
    protected $cryptoPriceService;
    protected $cryptoService;

    public function __construct(CryptoPriceService $cryptoPriceService, CryptoPriceService $cryptoService)
    {
        $this->cryptoPriceService = $cryptoPriceService;
        $this->cryptoService = $cryptoService;

    }

    public function index()
    {
        $cryptos = $this->cryptoPriceService->getTopCryptos();

        return view('cryptos.index', ['cryptos' => $cryptos]);
    }

    public function updateCryptoData()
    {
        $this->cryptoService->updateCryptoData();
        return response()->json(['message' => 'Cryptocurrency data updated successfully.']);
    }
}
