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

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Gestión de Pools</h2>

        <!-- Overview Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Total MarketCap -->
            <div class="bg-green-800 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">Total Market Cap</div>
                <div class="text-3xl font-bold">${{ $marketCap }}M</div>
            </div>
            <!--  24h Volume -->
            <div class="bg-green-700 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">24h Volume</div>
                <div class="text-3xl font-bold">${{ $totalVolume }}M</div>
            </div>
            <!-- GAIA Price -->
            <div class="bg-green-600 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">GAIA Price</div>
                <div class="text-3xl font-bold">${{ $gaiaPrice }}</div>
            </div>
        </div>


        <!-- My Pools Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white mb-8">
            <div class="mb-4 flex justify-between items-center">
                <div class="text-lg">Mis Pools</div>
                <button class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
                    data-bs-toggle="modal" data-bs-target="#createPoolModal">Crear Pool</button>
            </div>

            <!-- List of My Pools -->
            <div class="mt-8">
                <div class="divide-y divide-gray-700">
                    <div class="py-4 grid grid-cols-5 gap-4 items-center">
                        <div class="font-bold">Nombre del Pool</div>
                        <div class="font-bold text-center">Descripción</div>
                        <div class="font-bold text-center">Token 1</div>
                        <div class="font-bold text-center">Token 2</div>
                    </div>
                    @foreach ($myPools as $pool)
                        <div class="py-4 grid grid-cols-5 gap-4 items-center">
                            <div>{{ $pool->name }}</div>
                            <div class="text-center">{{ $pool->description }}</div>
                            <div class="text-center flex items-center justify-center">
                                @if ($pool->token1)
                                    <img src="{{ $pool->token1->url ?? 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                        alt="Token   1 Placeholder" class="rounded-circle h-6 w-6 mx-1">
                                    {{ $pool->token1->name }}
                                @else
                                    <span class="text-gray-500">Token 1 no asignado</span>
                                @endif
                            </div>
                            <div class="text-center flex items-center justify-center ">
                                @if ($pool->token2)
                                    <img src="{{ $pool->token2->url ?? 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                        alt="Token   2 Placeholder" class="rounded-circle  h-6 w-6 mx-1">
                                    {{ $pool->token2->name }}
                                @else
                                    <span class="text-gray-500">Token 2 no asignado</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <!-- Botón para añadir liquidez -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLiquidityModal"
                                    data-user-id="{{ $user->id }}" data-pool-id="{{ $pool->id }}">Añadir
                                    Liquidez</button>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- Other Pools Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white mb-8">
            <div class="text-lg mb-4">Otras Pools</div>
            <div class="py-4 grid grid-cols-4 gap-4 items-center">
                <div class="font-bold">Nombre del Pool</div>
                <div class="font-bold text-center">Descripción</div>
                <div class="font-bold text-center">Liquidez</div>
            </div>
            <div class="divide-y divide-gray-700">
                @foreach ($allPools as $pool)
                    @if (!$myPools || !$myPools->contains($pool))
                        <div class="py-4 grid grid-cols-4 gap-4">
                            <div>{{ $pool->name }}</div>
                            <div class="text-center">{{ $pool->description }}</div>
                            <div class="text-center">{{ $pool->total_liquidity }}</div>
                            <div class="text-right">
                                <button
                                    class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Unirse</button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <!-- Modal para añadir liquidez -->
    <div class="modal fade" id="addLiquidityModal" tabindex="-1" aria-labelledby="addLiquidityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLiquidityModalLabel">Añadir Liquidez</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addLiquidity', ['userId' => $user->id, 'poolId' => $pool->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="token1_amount" class="form-label">Cantidad de Token 1</label>
                            <input type="number" class="form-control" id="token1_amount" name="token1_amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="token2_amount" class="form-label">Cantidad de Token 2</label>
                            <input type="number" class="form-control" id="token2_amount" name="token2_amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Añadir Liquidez</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Create Pool Modal -->
    <div class="modal fade" id="createPoolModal" tabindex="-1" aria-labelledby="createPoolModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #0d7936; color: white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPoolModalLabel">Create Pool</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('createPool') }}" method="POST">
                    @csrf <div class="modal-body">
                        <div id="createPoolView">
                            <div class="mb-8">

                                <!-- Pool Name and Description -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-200">Nombre de la
                                        Pool</label>
                                    <input type="text" id="name" name="name"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-black border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-200">Descripción</label>
                                    <textarea id="description" name="description"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-black border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                        required></textarea>
                                </div>

                                <!-- Token Selection -->
                                <div class="container mx-auto mt-5">
                                    <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white mb-8">
                                        <!-- Token Selection Area -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                            <!-- Token 1 Selection modified-->
                                            <div class="relative">
                                                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                                                    <div class="flex items-center">
                                                        <span class="rounded-full p-2 bg-green-800 mr-3">
                                                            <!-- Token Icon Placeholder -->
                                                            <img id="tokenIcon" src="" alt="Token Icon"
                                                                class="rounded-circle h-6 w-6">
                                                        </span>
                                                        <select id="token1Dropdown" name="token1"
                                                            class="bg-transparent text-black focus:outline-none appearance-none">
                                                            @foreach ($tokens as $token)
                                                                <option value="{{ $token->id }}"
                                                                    data-icon-url="{{ $token->url ?? 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}">
                                                                    {{ $token->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                            <path
                                                                d="M5.516 7.548c.436 0 .84.28.993.683l1.55 4.714c.153.403.544.683.994.683h5.896c.668 0 1.207-.539 1.207-1.207 0-.668-.539-1.207-1.207-1.207H10.6l1.55-4.714a1.217 1.217 0 00-.993-1.683H5.516c-.668.047-1.207.586-1.207 1.254 0 .668.539 1.207 1.207 1.207z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Token 2 Selection -->
                                            <div class="relative">
                                                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                                                    <div class="flex items-center">
                                                        <span class="rounded-full p-2 bg-green-800 mr-3">
                                                            <!-- Token Icon Placeholder -->
                                                            <img id="tokenIcon2" src="" alt="Token Icon"
                                                                class="rounded-circle h-6 w-6">
                                                        </span>
                                                        <select id="token2Dropdown" name="token2"
                                                            class="bg-transparent text-black focus:outline-none appearance-none">
                                                            @foreach ($tokens as $token)
                                                                <option value="{{ $token->id }}"
                                                                    data-icon-url="{{ $token->url ?? 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}">
                                                                    {{ $token->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                            <path
                                                                d="M5.516 7.548c.436 0 .84.28.993.683l1.55 4.714c.153.403.544.683.994.683h5.896c.668 0 1.207-.539 1.207-1.207 0-.668-.539-1.207-1.207-1.207H10.6l1.55-4.714a1.217 1.217 0 00-.993-1.683H5.516c-.668.047-1.207.586-1.207 1.254 0 .668.539 1.207 1.207 1.207z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </ <!-- Creation Button -->
                                        <div class="text-center mt-8">
                                            <button id="createPoolButton"
                                                class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                                Create Pool
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@if (session('info'))
    <div class="alert alert-success">
        {{ session('info') }}
    </div>
@endif
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#addLiquidityModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var userId = button.data('user-id'); // Extrae el ID del usuario
                var poolId = button.data('pool-id'); // Extrae el ID de la pool

                var modal = $(this);
                modal.find('form').attr('action', "{{ route('addLiquidity') }}" + "?userId=" + userId +
                    "&poolId=" + poolId);
            });

            // Función para cargar los tokens y llenar las opciones del dropdown
            function loadTokens(dropdownId, userId) {
                $.getJSON('/tokens', {
                    userId: userId
                }, function(tokens) {
                    const dropdown = $(`#${dropdownId}`);
                    dropdown.empty(); // Limpia las opciones existentes
                    $.each(tokens, function(i, token) {
                        dropdown.append($(`<option value="${token.id}">${token.name}</option>`));
                    });
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading tokens:', textStatus, errorThrown);
                });
            }

            // Llama a la función para cargar los tokens y llenar las opciones del primer dropdown
            loadTokens('token1Dropdown', {{ auth()->id() }});
            // Llama a la función para cargar los tokens y llenar las opciones del segundo dropdown
            loadTokens('token2Dropdown', {{ auth()->id() }});
        });
    </script>
@endpush
