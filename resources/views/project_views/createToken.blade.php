@extends('layouts.general')

@section('create_token')
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <button onclick="openModal()" class="px-4 py-2 bg-blue-500 text-white rounded">Open Modal</button>

    {{-- backdrop --}}
    <div class="fixed inset-0 bg-black opacity-50 hidden" id="modal-backdrop"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center hidden" id="modal-container">
        <!-- Actual Modal Content -->
        <div class="bg-white p-6 rounded shadow-lg relative">
            <!-- Close Button -->
            <button onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0  0  24  24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6  18  18  6M6  6l12  12" />
                </svg>
            </button>

            <!-- Your Form Goes Here -->
            <form method="post" action="{{ route('create.token', ['userId' => $userId]) }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Memecoin" required
                        maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="symbol" class="form-label">Symbol</label>
                    <input type="text" class="form-control" id="symbol" name="symbol" value="{{ old('symbol') }}"
                        placeholder="MMC" required maxlength="10">
                </div>
                <div class="mb-3">
                    <label for="totalSupply" class="form-label">Total Supply</label>
                    <input type="number" class="form-control" id="totalSupply" name="totalSupply" placeholder="14000"
                        required min="100">
                </div>
                <div class="mb-3">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" class="form-control" id="url" name="url"
                        placeholder="Enter URL for the coin" required>
                </div>
                <button type="submit" class="mt-2 px-4 py-2 bg-green-500 text-white rounded">Create Token</button>
                <button onclick="closeModal()" class="mt-2 px-4 py-2 bg-red-500 text-white rounded">Close</button>

            </form>
        </div>
    </div>

@endsection
