@extends('auth.template')
@section('content')
    {{-- SIDEBAR --}}
    <div class="container col-1 bg-dark rounded-5 ms-2 d-grid justify-content-center align-items-center">
        <div></div>
        <a class="btn rounded-5 btn-lg btn-secondary mt-5" href="{{ url('/home') }}">
            <i class="fa-solid fa-person-swimming"></i>
        </a>
        <a class="btn rounded-5 btn-lg btn-secondary" href="{{ url('/tokens/all') }}">
            <i class="fa-brands fa-bitcoin"></i>
        </a>
        <a class="btn rounded-5 btn-lg btn-secondary" href="{{ url('/tokens/view') }}">
            <i class="fa-solid fa-coins"></i>
        </a>
        <a class="btn rounded-5 btn-lg btn-secondary" href="{{ url('/transactions/all') }}">
            <i class="fa-solid fa-receipt"></i>
        </a>
        <a class="btn rounded-5 btn-lg btn-secondary mb-5" href="{{ url('/swap') }}">
            <i class="fa-solid fa-retweet"></i> </a>
        <div></div>
    </div>
    {{-- CONTENIDO PRINCIPAL --}}
    <div class="container mx-auto mt-5">
        <h1 class="text-center pb-1"><span class="rounded-pill title-custom px-3 display-6">Intercambio de tokens</span>
        </h1>
        <!-- Token 1 Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
            <h3 class="text-lg font-bold mb-4">Token 1</h3>
            <!-- Token 1 Selection Area -->
            <div class="relative">
                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                    <div class="flex items-center">
                        <span class="rounded-full p-2 bg-green-800 mr-3">
                            <!-- Token Icon Placeholder -->
                            <img id="token1Image"
                                src="{{ $selectedToken1 ? $selectedToken1->url : 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                alt="Token Icon" class="h-6 w-6">
                        </span>
                        <select id="token1Dropdown" name="token1"
                            class="bg-transparent text-black focus:outline-none appearance-none">
                            @foreach ($tokens as $token)
                                <option value="{{ $token->name }}">{{ $token->name }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-300">Cantidad:</span>
                            <input type="number" name="token1Amount" id="token1Amount"
                                class="text-white border-none bg-success h2 text-end rounded-2" value="0"
                                placeholder="Ingrese la cantidad" max="{{ $userToken1Amount }}">
                            <span>Saldo: <span id="userToken1Amount">{{ $userToken1Amount }}</span></span>
                        </div>
                    </div>
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
            <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
                <h3 class="text-lg font-bold mb-4">Token 2</h3>
                <!-- Token 2 Selection Area -->
                <div class="relative">
                    <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                        <div class="flex items-center">
                            <span class="rounded-full p-2 bg-green-800 mr-3">
                                <!-- Token Icon Placeholder -->
                                <img id="token2Image"
                                    src="{{ $selectedToken2 ? $selectedToken2->url : 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                    alt="Token Icon" class="h-6 w-6">
                            </span>
                            <select id="token2Dropdown" name="token2"
                                class="bg-transparent text-black focus:outline-none appearance-none">
                                @foreach ($tokens as $token)
                                    <option value="{{ $token->name }}">{{ $token->name }}</option>
                                @endforeach
                            </select>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-300">Cantidad:</span>
                                <input type="number" name="token2Amount" id="token2Amount"
                                    class="text-white border-none bg-success h2 text-end rounded-2" value="0"
                                    placeholder="" readonly>
                                <span>Saldo: <span id="userToken2Amount">{{ $userToken2Amount }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Swap Button -->
            <div class="text-center mt-8">
                <form id="swapForm" action="{{ route('swap.tokens') }}" method="post">
                    @csrf
                    <input type="hidden" name="token1" id="token1Input">
                    <input type="hidden" name="token2" id="token2Input">
                    <input type="hidden" name="amount" id="amountInput">
                    <button type="submit" class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Realizar Intercambio
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="errorMessage" class="error-message"></div>
@endsection
