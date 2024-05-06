<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gaia Protocol') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.4/dist/tailwind.min.css" rel="stylesheet">
    {{-- script crear token modal --}}
    <script defer src="{{ asset('js/createTokenModal.js') }}"></script>
    <script defer src="{{ asset('js/changeTokenUrl.js') }}"></script>

    <!-- Styles -->
    @php
        $isCryptos = Route::currentRouteName() === 'cryptos.index';
        $isSwap = Route::currentRouteName() === 'swap';
        $isTransactions = Route::currentRouteName() === 'showAll.transactions';
        $isMyTokens = Route::currentRouteName() === 'showMy.tokens';
        $isAllTokens = Route::currentRouteName() === 'showAll.tokens';
        $isPools = Route::currentRouteName() === 'home';
    @endphp
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    @vite(['resources/js/app.js', 'resources/css/app.scss', 'resources/css/app.css', 'resources/css/custom.scss', 'resources/css/custom.css', $isCryptos ? 'resources/css/cryptos.scss' : null, $isSwap ? 'resources/js/swap.js' : null, $isTransactions ? 'resources/css/transactions.css' : null, $isAllTokens ? 'resources/css/allTokens.css' : null, $isMyTokens ? 'resources/css/myTokens.css' : null, $isSwap ? 'resources/css/swap.css' : null, $isPools ? 'resources/js/pools.js' : null, $isPools ? 'resources/css/pools.css' : null])

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-dark shadow-sm d-flex justify-content-between px-3 py-2">
            <div class="sidebar2 d-flex flex-grow-1">
                <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <div class="dropdown flex-row-reverse">
                                <button class="btn btn-secondary rounded-5" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                    <i class="fa-solid fa-user"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                {{-- @endif --}}
                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="pt-4 d-flex">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
</body>

</html>
