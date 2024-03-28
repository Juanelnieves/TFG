@extends('auth.template')

@section('content')
    {{-- SIDEBAR --}}
    <div class="container col-1 bg-warning rounded-5 ms-2 d-grid justify-content-center align-items-center">
        <div></div>
        <a><button class="btn rounded-5 btn-secondary mt-5">A</button></a>
        <a><button class="btn rounded-5 btn-secondary ">A</button></a>
        <a><button class="btn rounded-5 btn-secondary">A</button></a>
        <a><button class="btn rounded-5 btn-secondary">A</button></a>
        <a><button class="btn rounded-5 btn-secondary mb-5">A</button></a>
        <div></div>
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Gestión de Pools</h2>

        <!-- Overview Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Total Liquidity -->
            <div class="bg-green-800 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">Total Liquidity</div>
                <div class="text-3xl font-bold">$330,338,833</div>
            </div>
            <!--  24h Volume -->
            <div class="bg-green-700 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">24h Volume</div>
                <div class="text-3xl font-bold">$4,336,033</div>
            </div>
            <!-- GAIA Price -->
            <div class="bg-green-600 p-4 rounded-lg shadow-lg text-white text-center">
                <div class="text-lg">GAIA Price</div>
                <div class="text-3xl font-bold">$0.33</div>
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
                    <div class="py-4 grid grid-cols-4 gap-4 items-center">
                        <div class="font-bold">Nombre del Pool</div>
                        <div class="font-bold text-center">Descripción</div>
                        <div class="font-bold text-center">Token 1</div>
                        <div class="font-bold text-center">Token 2</div>
                    </div>
                    @foreach ($myPools as $pool)
                        <div class="py-4 grid grid-cols-4 gap-4 items-center">
                            <div>{{ $pool->name }}</div>
                            <div class="text-center">{{ $pool->description }}</div>
                            <div class="text-center flex items-center justify-center">
                                @if ($pool->token1)
                                    <img src="{{ $pool->token1->url }}"
                                        alt="Token   1 Placeholder" class="rounded-circle h-6 w-6 mx-1">
                                    {{ $pool->token1->name }}
                                @else
                                    <span class="text-gray-500">Token 1 no asignado</span>
                                @endif
                            </div>
                            <div class="text-center flex items-center justify-center ">
                                @if ($pool->token2)
                                    <img src="{{ $pool->token2->url }}"
                                        alt="Token   2 Placeholder" class="rounded-circle  h-6 w-6 mx-1">
                                    {{ $pool->token2->name }}
                                @else
                                    <span class="text-gray-500">Token 2 no asignado</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- Other Pools Section -->
        <div class="bg-green-900 p-4 rounded-lg shadow-lg text-white mb-8">
            <div class="text-lg mb-4">Otras Pools</div>
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


    <!-- Create Pool Modal -->
    <div class="modal fade" id="createPoolModal" tabindex="-1" aria-labelledby="createPoolModalLabel" aria-hidden="true">
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
                                                                    data-icon-url="{{ $token->url }}">
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
                                                                    data-icon-url="{{ $token->url }}">
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


@push('styles')
    <style>
        body {
            background-color: #0f172a;
        }

        .text-2xl,
        .text-3xl,
        .text-lg {
            color: #e2e8f0;
            /* A light grey color for text */
        }

        .bg-green-800 {
            background-color: #0d7936;
            /* A deep green color */
        }

        .bg-green-700 {
            background-color: #0d9a43;
            /* A slightly lighter green */
        }

        .bg-green-900 {
            background-color: #119443;
            /* An even deeper green color */
        }

        .hover\:bg-green-600:hover {
            background-color: #25eb3c;
            /* A hover state color */
        }

        .rounded-lg {
            border-radius: 0.5rem;
            /* Rounded corners */
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            /* Shadow for depth */
        }

        .font-bold {
            font-weight: 700;
            /* Bold font */
        }

        .py-2 {
            padding-top: 0.5rem;
            /* Y-axis padding */
            padding-bottom: 0.5rem;
        }

        .px-4 {
            padding-left: 1rem;
            /* X-axis padding */
            padding-right: 1rem;
        }

        .mb-3,
        .mb-4,
        .mb-6,
        .mb-8 {
            margin-bottom: 1rem;
            /* Bottom margin variations */
        }

        .mt-5,
        .mt-8 {
            margin-top: 1.25rem;
            /* Top margin variations */
        }

        .text-center {
            text-align: center;
            /* Center text alignment */
        }

        .text-right {
            text-align: right;
            /* Right text alignment */
        }

        .grid {
            display: grid;
            /* Grid layout */
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            /* Three columns grid */
        }

        .gap-4 {
            gap: 1rem;
            /* Spacing between grid items */
        }

        .divide-y> :not([hidden])~ :not([hidden]) {
            --tw-divide-y-reverse: 0;
            border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse)));
            border-bottom-width: calc(1px * var(--tw-divide-y-reverse));
        }

        .divide-green-700> :not([hidden])~ :not([hidden]) {
            --tw-divide-opacity: 1;
            border-color: rgba(55, 65, 81, var(--tw-divide-opacity));
            /* Divider color */
        }

        .modal-content {
            background-color: #119443;
            /* Green background for the modal content */
            color: white;
            /* White text color */
        }

        .modal-header {
            border-bottom: 1px solid #0d7936;
            /* Border bottom for the header */
        }

        .modal-footer {
            border-top: 1px solid #0d7936;
            /* Border top for the footer */
        }

        .modal-title {
            color: white;
            /* White title text color */
        }

        .btn-close {
            color: white;
            /* White close button color */
        }

        /* Form styles */
        .form-control {
            background-color: #0f172a;
            /* Dark background for form inputs */
            color: white;
            /* White text color for form inputs */
            border: 1px solid #0d7936;
            /* Border color for form inputs */
        }

        .form-control:focus {
            border-color: #25eb3c;
            /* Border color on focus for form inputs */
            box-shadow: 0 0 0 0.2rem rgba(37, 226, 60, 0.25);
            /* Box shadow on focus for form inputs */
        }

        .btn {
            color: white;
            /* White text color for buttons */
            border: none;
            /* No border for buttons */
        }

        .btn:hover {
            background-color: #25eb3c;
            /* Hover background color for buttons */
        }

        .btn:active {
            background-color: #0d7936;
            /* Active background color for buttons */
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
                    // Función para cargar los tokens y llenar las opciones del dropdown
                    function loadTokens(dropdownId) {
                        $.getJSON('/tokens', function(tokens) {
                                const dropdown = $(`#${dropdownId}`);
                                dropdown.empty(); // Limpia las opciones existentes
                                $.each(tokens, function(i, token) {
                                        dropdown.append($(`<option value="${token.id}">${token.name}</option>`);
                                        });
                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                console.error('Error loading tokens:', textStatus, errorThrown);
                            });
                        }

                        // Llama a la función para cargar los tokens y llenar las opciones del primer dropdown
                        loadTokens('token1Dropdown');
                        // Llama a la función para cargar los tokens y llenar las opciones del segundo dropdown
                        loadTokens('token2Dropdown');
                    });
    </script>
@endpush
