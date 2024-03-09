@extends('auth.template')

@section('content')
    {{-- SIDEBAR --}}
    <div class="container col-1 bg-dark rounded-5 ms-2 d-grid justify-content-center align-items-center">
        <div></div>
        <a class="btn rounded-5 btn-lg btn-secondary mt-5" href="{{ url('/pools') }}">
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
        <h1 class="text-center pb-1"><span class="rounded-pill title-custom px-3 display-6">Intercambio de tokens</span></h1>
        <!-- Token 1 Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
            <h3 class="text-lg font-bold mb-4">Token 1</h3>

            <!-- Token 1 Selection Area -->
            <div class="relative">
                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                    <div class="flex items-center">
                        <span class="rounded-full p-2 bg-green-800 mr-3">
                            <!-- Token Icon Placeholder -->
                            <img src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/ethereum-eth-icon.png"
                                alt="Token Icon" class="h-6 w-6">
                        </span>
                        <select id="token1Dropdown" name="token1"
                            class="bg-transparent text-black focus:outline-none appearance-none">
                            {{-- Consultar los nombres de los tokens directamente en el Blade --}}
                            @php
                                $tokenNames = DB::table('tokens')->pluck('name');
                            @endphp

                            {{-- Iterar sobre la colección de nombres de tokens y agregar opciones --}}
                            @foreach ($tokenNames as $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M5.516 7.548c.436 0 .84.28.993.683l1.55 4.714c.153.403.544.683.994.683h5.896c.668 0 1.207-.539 1.207-1.207 0-.668-.539-1.207-1.207-1.207H10.6l1.55-4.714a1.217 1.217 0 00-.993-1.683H5.516c-.668.047-1.207.586-1.207 1.254 0 .668.539 1.207 1.207 1.207z" />
                        </svg>
                    </div>

                </div>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-300">Cantidad:</span>
                <input type="number" name="token1Amount" id="token1Amount"
                    class="text-white border-none bg-success h2 text-end rounded-2" value="1000"
                    placeholder="Ingrese la cantidad">
            </div>
        </div>
        <br><br>
        <!-- Token 2 Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
            <h3 class="text-lg font-bold mb-4">Token 2</h3>

            <!-- Token 2 Selection Area -->
            <div class="relative">
                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                    <div class="flex items-center">
                        <span class="rounded-full p-2 bg-green-800 mr-3">
                            <!-- Token Icon Placeholder -->
                            <img src="https://upload.wikimedia.org/wikipedia/en/b/b9/Solana_logo.png" alt="Token Icon"
                                class="h-6 w-6">
                        </span>
                        <select id="token2Dropdown" name="token2"
                            class="bg-transparent text-black focus:outline-none appearance-none">
                            {{-- Iterar sobre la colección de nombres de tokens y agregar opciones --}}
                            @foreach ($tokenNames as $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M5.516 7.548c.436 0 .84.28.993.683l1.55 4.714c.153.403.544.683.994.683h5.896c.668 0 1.207-.539 1.207-1.207 0-.668-.539-1.207-1.207-1.207H10.6l1.55-4.714a1.217 1.217 0 00-.993-1.683H5.516c-.668.047-1.207.586-1.207 1.254 0 .668.539 1.207 1.207 1.207z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-300">Cantidad:</span>
                <input type="number" name="token2Amount" id="token2Amount"
                    class="text-white border-none bg-success h2 text-end rounded-2" value="500"
                    placeholder="Ingrese la cantidad">
            </div>
        </div>




        <!-- Swap Button -->
        <div class="text-center mt-8">
            <button id="swapTokensButton" class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Realizar Intercambio
            </button>
        </div>
    </div>

    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
