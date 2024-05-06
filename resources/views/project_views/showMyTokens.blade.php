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
        <h1 class="text-center pb-1"><span class="title px-6 display-6" id="title">Mis tokens</span></h1>
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white">
            <div class="flex justify-center overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm text-white uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-sm text-white uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-sm text-white uppercase tracking-wider">Símbolo</th>
                            <th class="px-6 py-3 text-left text-sm text-white uppercase tracking-wider">Suministro Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-green-700 divide-y divide-gray-200">
                        @foreach ($tokens as $token)
                            <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-green-800' : 'bg-green-700' }}">
                                <td class="px-6 py-3 whitespace-nowrap">{{ $token->id }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ $token->name }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ strtoupper($token->symbol) }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">{{ $token->total_supply }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center mt-4">
                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded" data-bs-toggle="modal" data-bs-target="#createTokenModal">
                    Crear Token
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createTokenModal" tabindex="-1" aria-labelledby="createTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-green-800">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="createTokenModalLabel">Crear Token</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('create.token', ['userId' => $userId]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label text-white">Name</label>
                            <input type="text" class="form-control bg-green-700 text-white" id="name" name="name" placeholder="Memecoin" required maxlength="50">
                        </div>
                        <div class="mb-3">
                            <label for="symbol" class="form-label text-white">Symbol</label>
                            <input type="text" class="form-control bg-green-700 text-white" id="symbol" name="symbol" value="{{ old('symbol') }}" placeholder="MMC" required maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label for="totalSupply" class="form-label text-white">Total Supply</label>
                            <input type="number" class="form-control bg-green-700 text-white" id="totalSupply" name="totalSupply" placeholder="14000" required min="100" max="1000000000">
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label text-white">URL</label>
                            <input type="url" class="form-control bg-green-700 text-white" id="url" name="url" placeholder="Enter URL for the coin" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">Create Token</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection