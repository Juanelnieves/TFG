<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CryptoPriceService;

class CryptoController extends Controller
{
    protected $cryptoPriceService;

    public function __construct(CryptoPriceService $cryptoPriceService)
    {
        $this->cryptoPriceService = $cryptoPriceService;
    }

    public function index()
    {
        $cryptos = $this->cryptoPriceService->getTopCryptos();

        return view('cryptos.index', ['cryptos' => $cryptos]);
    }
}
