@extends('auth.template')
@section('content')
    {{-- SIDEBAR --}}
    <div class="col-1 bg-dark rounded-5 d-flex flex-column justify-content-center align-items-center sidebar">
        <a class="btn rounded-5 btn-md btn-secondary my-4" href="{{ url('/home') }}">
            <i class="fa-solid fa-person-swimming"></i>
        </a>
        <a class="btn rounded-5 btn-md btn-secondary my-4" href="{{ url('/tokens/all') }}">
            <i class="fa-brands fa-bitcoin"></i>
        </a>
        <a class="btn rounded-5 btn-md btn-secondary my-4" href="{{ url('/tokens/view') }}">
            <i class="fa-solid fa-coins"></i>
        </a>
        <a class="btn rounded-5 btn-md btn-secondary my-4" href="{{ url('/transactions/all') }}">
            <i class="fa-solid fa-receipt"></i>
        </a>
        <a class="btn rounded-5 btn-md btn-secondary my-4" href="{{ url('/swap') }}">
            <i class="fa-solid fa-retweet"></i>
        </a>
    </div>
    {{-- CONTENIDO PRINCIPAL --}}
    <div class="container">
        <h1 class="text-center pb-1"><span class="title px-6 display-6" id="title">Intercambio de tokens</span>
        </h1>
        <!-- Token Swap Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
            <!-- Token 1 Section -->
            <div class="mb-4">
                <h3 class="text-lg font-bold mb-4">Token 1</h3>
                <!-- Token 1 Selection Area -->
                <div class="relative">
                    <div class="flex justify-between items-center bg-green-800 p-3 rounded-lg" id="fondo1">
                        <div class="flex justify-between items-center bg-green-800 p-3 rounded-lg">
                            <div class="flex items-center">
                                <span class="rounded-full p-2 mr-3">
                                    <!-- Token Icon Placeholder -->
                                    <img id="token1Image"
                                        src="{{ $selectedToken1 ? $selectedToken1->url : 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                        alt="Token Icon" class="h-6 w-6">
                                </span>
                                <div class="token-select-wrapper token1-select-wrapper">
                                    <select id="token1Dropdown" name="token1"
                                        class="bg-transparent focus:outline-none appearance-none">
                                        @foreach ($tokens as $token)
                                            <option value="{{ $token->name }}" data-image="{{ $token->url }}">
                                                <img src="{{ $token->url }}" alt="{{ $token->name }}"
                                                    class="inline-block h-4 w-4 mr-2">
                                                {{ $token->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="number" name="token1Amount" id="token1Amount"
                                    class="text-white border-none h2 text-end rounded-2" value="0"
                                    placeholder="Ingrese la cantidad" max="{{ $userToken1Amount }}">
                            </div>
                        </div>
                    </div>
                    <span class="ml-2">Saldo: <span
                        id="userToken1Amount">{{ $userToken1Amount }}</span></span>
                </div>
            </div>
             <!-- Flecha de intercambio -->
             <div class="text-center mt-4">
                <button type="button" id="swapTokensButton"
                    class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <!-- Token 2 Section -->
            <div>
                <h3 class="text-lg font-bold mb-4">Token 2</h3>
                <!-- Token 2 Selection Area -->
                <div class="relative">
                    <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg" id="fondo2">
                        <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                            <div class="flex items-center">
                                <span class="rounded-full p-2 mr-3">
                                    <!-- Token Icon Placeholder -->
                                    <img id="token2Image"
                                        src="{{ $selectedToken2 ? $selectedToken2->url : 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                        alt="Token Icon" class="h-6 w-6">
                                </span>
                                <div class="token-select-wrapper token1-select-wrapper">

                                    <select id="token2Dropdown" name="token2"
                                        class="bg-transparent focus:outline-none appearance-none">
                                        @foreach ($tokens as $token)
                                            <option value="{{ $token->name }}">{{ $token->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="number" name="token2Amount" id="token2Amount"
                                    class="text-white border-none h2 text-end rounded-2" value="0" placeholder=""
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <span class="ml-2">Saldo: <span
                        id="userToken2Amount">{{ $userToken2Amount }}</span></span>
                </div>
            </div>
            <!-- Swap Button -->
            <div class="text-center mt-8">
                <form id="swapForm" action="{{ route('swap.tokens') }}" method="post">
                    @csrf
                    <input type="hidden" name="token1" id="token1Input">
                    <input type="hidden" name="token2" id="token2Input">
                    <input type="hidden" name="amount" id="amountInput">
                    <button type="submit" class="bg-green-700 hover:bg-green-600 font-bold py-2 px-4 rounded">
                        Realizar Intercambio
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="errorMessage" class="error-message"></div>
@endsection
