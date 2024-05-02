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
        <h1 class="text-center pb-1"><span class="title px-6 display-6" id="title">Transacciones</span></h1>
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
            <div class="flex justify-center overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-800">
                        <th class="px-6 py-3 text-left text-base text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-base text-white uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-base text-white uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-base text-white uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-base text-white uppercase tracking-wider">id del Usuario</th>
                    </thead>
                    <tbody class="bg-green-700 divide-y divide-gray-200">
                        @foreach ($transactions as $transaction)
                            <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-green-800' : 'bg-green-700' }}">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->user->id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
