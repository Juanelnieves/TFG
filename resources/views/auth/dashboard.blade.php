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
    <div class="container mx-auto">
        <h1 class="text-center pb-1"><span class="title px-6 display-6" id="title">Gestión de Pools</span></h1>
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
            <div class="bg-green-700 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">GAIA Price</div>
                <div class="text-3xl font-bold">${{ $gaiaPrice }}</div>
            </div>
        </div>


        <!-- My Pools Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white mb-8">
            <div class="mb-4 flex justify-between items-center">
                <div class="text-lg">Mis Pools</div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPoolModal">Crear Pool</button>
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
            {{ $myPools->appends(['all_pools_page' => $allPools->currentPage()])->links('pagination::tailwind-my') }}

        </div>


        <!-- Other Pools Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white mb-8">
            <div class="mb-4 flex justify-between items-center">
                <div class="text-lg">Otras Pools</div>
            </div>

            <!-- List of Other Pools -->
            <div class="mt-8">
                <div class="divide-y divide-gray-700">
                    <div class="py-4 grid grid-cols-5 gap-4 items-center">
                        <div class="font-bold">Nombre del Pool</div>
                        <div class="font-bold text-center">Descripción</div>
                        <div class="font-bold text-center">Token 1</div>
                        <div class="font-bold text-center">Token 2</div>
                    </div>
                    @foreach ($allPools as $pool)
                        @if (!$myPools || !$myPools->contains($pool))
                            <div class="py-4 grid grid-cols-5 gap-4 items-center">
                                <div>{{ $pool->name }}</div>
                                <div class="text-center">{{ $pool->description }}</div>
                                <div class="text-center flex items-center justify-center">
                                    @if ($pool->token1)
                                        <img src="{{ $pool->token1->url ?? 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                            alt="Token 1 Placeholder" class="rounded-circle h-6 w-6 mx-1">
                                        {{ $pool->token1->name }}
                                    @else
                                        <span class="text-gray-500">Token 1 no asignado</span>
                                    @endif
                                </div>
                                <div class="text-center flex items-center justify-center">
                                    @if ($pool->token2)
                                        <img src="{{ $pool->token2->url ?? 'https://cdn-icons-png.freepik.com/512/5266/5266579.png' }}"
                                            alt="Token 2 Placeholder" class="rounded-circle h-6 w-6 mx-1">
                                        {{ $pool->token2->name }}
                                    @else
                                        <span class="text-gray-500">Token 2 no asignado</span>
                                    @endif
                                </div>
                                <div class="text-right mr-5">
                                    <!-- Botón para unirse a la pool -->
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addLiquidityModal" data-user-id="{{ $user->id }}"
                                        data-pool-id="{{ $pool->id }}">Unirse</button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            {{ $allPools->appends(['my_pools_page' => $myPools->currentPage()])->links('pagination::tailwind-all') }}
        </div>
    </div>

    <!-- Modal para añadir liquidez -->
    <div class="modal fade" id="addLiquidityModal" tabindex="-1" aria-labelledby="addLiquidityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-green-700 text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLiquidityModalLabel">Añadir Liquidez</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @php
                    Log::info('Pool en la vista antes del form:', ['pool' => $pool]);
                @endphp
                <form id="addLiquidityForm" action="{{ route('addLiquidity') }}" method="POST">
                    @csrf
                    <input type="hidden" name="poolId" id="poolIdInput">
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
                @php
                    Log::info('Pool en la vista despues del form:', ['pool' => $pool]);
                @endphp
            </div>
        </div>
    </div>


    <!-- Create Pool Modal -->
    <div class="modal fade" id="createPoolModal" tabindex="-1" aria-labelledby="createPoolModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-green-700">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="createPoolModalLabel">Create Pool</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('createPool') }}" method="POST">
                    @csrf
                    <div class="modal-body">
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
                                            <!-- Token 1 Selection -->
                                            <div class="relative">
                                                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                                                    <div class="flex items-center">
                                                        <span class="rounded-full p-2 bg-green-800 mr-3">
                                                            <!-- Token Icon Placeholder -->
                                                            <img id="token1Image"
                                                                src="https://cdn-icons-png.freepik.com/512/5266/5266579.png"
                                                                alt="Token Icon" class="rounded-circle h-6 w-6">
                                                        </span>
                                                        <div class="token-select-wrapper token1-select-wrapper">
                                                            <select id="token1Dropdown" name="token1"
                                                                class="bg-transparent focus:outline-none appearance-none">
                                                                @foreach ($tokens as $token)
                                                                    <option value="{{ $token->id }}"
                                                                        data-image="{{ $token->url }}">
                                                                        {{ $token->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Token 2 Selection -->
                                            <div class="relative">
                                                <div class="flex justify-between items-center bg-green-700 p-3 rounded-lg">
                                                    <div class="flex items-center">
                                                        <span class="rounded-full p-2 bg-green-800 mr-3">
                                                            <!-- Token Icon Placeholder -->
                                                            <img id="token2Image"
                                                                src="https://cdn-icons-png.freepik.com/512/5266/5266579.png"
                                                                alt="Token Icon" class="rounded-circle h-6 w-6">
                                                        </span>
                                                        <div class="token-select-wrapper token2-select-wrapper">
                                                            <select id="token2Dropdown" name="token2"
                                                                class="bg-transparent focus:outline-none appearance-none">
                                                                @foreach ($tokens as $token)
                                                                    <option value="{{ $token->id }}"
                                                                        data-image="{{ $token->url }}">
                                                                        {{ $token->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Pool</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                console.log("El script se está ejecutando");

                const token1Dropdown = document.getElementById('token1Dropdown');
                const token2Dropdown = document.getElementById('token2Dropdown');
                const token1Image = document.getElementById('token1Image');
                const token2Image = document.getElementById('token2Image');
                const tokenSelects = document.querySelectorAll('.token-select-wrapper select');

                tokenSelects.forEach(function(select) {
                    const customOptions = Array.from(select.options).map(function(option) {
                        const imageSrc = option.getAttribute('data-image');
                        const label = option.textContent.trim();
                        return `<div class="token-option" data-value="${option.value}">
                                <img src="${imageSrc}" alt="${label}" class="inline-block h-4 w-4 mr-2">
                                <span>${label}</span>
                            </div>`;
                    });

                    const customDropdown = document.createElement('div');
                    customDropdown.classList.add('token-dropdown');
                    customDropdown.innerHTML = customOptions.join('');

                    const customSelect = document.createElement('div');
                    customSelect.classList.add('custom-select');
                    customSelect.innerHTML = `
                    <span class="selected-option">
                        <img src="${select.options[select.selectedIndex].getAttribute('data-image')}" alt="${select.options[select.selectedIndex].textContent.trim()}" class="inline-block h-4 w-4 mr-2">
                        <span>${select.options[select.selectedIndex].textContent.trim()}</span>
                    </span>
                    <i class="arrow"></i>
                `;

                    select.parentNode.insertBefore(customSelect, select);
                    select.parentNode.insertBefore(customDropdown, select.nextSibling);
                    select.style.display = 'none';

                    customSelect.addEventListener('click', function(event) {
                        event.stopPropagation();
                        customDropdown.classList.toggle('show');
                    });

                    customDropdown.addEventListener('click', function(event) {
                        const selectedOption = event.target.closest('.token-option');
                        if (selectedOption) {
                            const selectedValue = selectedOption.getAttribute('data-value');
                            select.value = selectedValue;
                            customSelect.querySelector('.selected-option').innerHTML = `
                            <img src="${selectedOption.querySelector('img').getAttribute('src')}" alt="${selectedOption.textContent.trim()}" class="inline-block h-4 w-4 mr-2">
                            <span>${selectedOption.textContent.trim()}</span>
                        `;
                            customDropdown.classList.remove('show');
                            updateTokenImage(select.id);
                        }
                    });

                    document.addEventListener('click', function(event) {
                        if (!customSelect.contains(event.target)) {
                            customDropdown.classList.remove('show');
                        }
                    });
                });

                function updateTokenImage(dropdownId) {
                    const dropdown = document.getElementById(dropdownId);
                    const selectedOption = dropdown.options[dropdown.selectedIndex];
                    const imageSrc = selectedOption.getAttribute('data-image');
                    const imageElement = document.getElementById(dropdownId.replace('Dropdown', 'Image'));
                    imageElement.src = imageSrc;
                }

                token1Dropdown.addEventListener('change', function() {
                    updateTokenImage('token1Dropdown');
                });

                token2Dropdown.addEventListener('change', function() {
                    updateTokenImage('token2Dropdown');
                });


                const token1Dropdown = document.getElementById('token1Dropdown');
                const token2Dropdown = document.getElementById('token2Dropdown');
                const token1Image = document.getElementById('token1Image');
                const token2Image = document.getElementById('token2Image');

                function updateTokenImage(dropdownId, imageElementId) {
                    const dropdown = document.getElementById(dropdownId);
                    const selectedOption = dropdown.options[dropdown.selectedIndex];
                    const imageSrc = selectedOption.getAttribute('data-image');
                    const imageElement = document.getElementById(imageElementId);
                    imageElement.src = imageSrc;
                }

                token1Dropdown.addEventListener('change', function() {
                    updateTokenImage('token1Dropdown', 'token1Image');
                });

                token2Dropdown.addEventListener('change', function() {
                    updateTokenImage('token2Dropdown', 'token2Image');
                });

                const addLiquidityButtons = document.querySelectorAll('[data-bs-target="#addLiquidityModal"]');
                addLiquidityButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const poolId = this.getAttribute('data-pool-id');
                        const poolIdInput = document.querySelector('#poolIdInput');
                        poolIdInput.value = poolId;
                        console.log('Pool ID actualizado en el formulario:', poolIdInput.value);
                    });
                });
                const addLiquidityForm = document.querySelector('#addLiquidityForm');
                addLiquidityForm.addEventListener('submit', function(event) {
                    const formData = new FormData(this);
                    console.log('Datos del formulario:', Object.fromEntries(formData));
                });
            });
        </script>
    @endpush
