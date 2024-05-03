@extends('auth.template')

@section('content')
    {{-- SIDEBAR --}}
    <div class="col-1 bg-dark rounded-4 d-flex flex-column justify-content-center align-items-center sidebar">
        <a class="btn rounded-4 btn-md my-4" href="{{ url('/home') }}">
            <img src="https://raydium.io/icons/entry-icon-pools.svg" alt="Pools Icon" class="pools-icon">
        </a>
        <a class="btn rounded-4 btn-md my-4" href="{{ url('/tokens/all') }}">
            <i class="fa-brands fa-bitcoin"></i>
        </a>
        <a class="btn rounded-4 btn-md my-4" href="{{ url('/tokens/view') }}">
            <i class="fa-solid fa-coins"></i>
        </a>
        <a class="btn rounded-4 btn-md my-4" href="{{ url('/transactions/all') }}">
            <i class="fa-solid fa-receipt"></i>
        </a>
        <a class="btn rounded-4 btn-md my-4" href="{{ url('/swap') }}">
            <img src="https://raydium.io/icons/entry-icon-swap.svg" alt="Swap Icon" class="swap-icon">
        </a>
    </div>
    <div class="container">
        <h1 class="text-center pb-1"><span class="title px-5 text-3xl" id="title">Todos los tokens</span></h1>
        <div class="bg-green-900 p-3 rounded-lg shadow-lg text-white">
            <div class="flex justify-center overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-green-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm text-white uppercase tracking-wider">
                                ID del Usuario
                            </th>
                            <th class="px-4 py-2 text-left text-sm text-white uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-4 py-2 text-left text-sm text-white uppercase tracking-wider">
                                Símbolo
                            </th>
                            <th class="px-4 py-2 text-left text-sm text-white uppercase tracking-wider">
                                Suministro Total
                            </th>
                            <th class="px-4 py-2 text-left text-sm text-white uppercase tracking-wider">
                                Precio
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-green-700 divide-y divide-gray-200">
                        @foreach ($tokens as $token)
                            <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-green-800' : 'bg-green-700' }}">
                                <td class="px-4 py-2 whitespace-nowrap">{{ $token->user_id }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $token->name }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ strtoupper($token->symbol) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $token->total_supply }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $token->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @endsection
