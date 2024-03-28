@extends('layouts.template')

@section('general')
    {{-- SIDEBAR --}}
    <div class="sidebar col-1 bg-warning rounded-5 d-grid justify-content-center align-items-center me-3">
        <a><button class="btn rounded-5 btn-secondary mt-2">A</button></a>
        <a><button class="btn rounded-5 btn-secondary ">A</button></a>
        <a><button class="btn rounded-5 btn-secondary">A</button></a>
        <a><button class="btn rounded-5 btn-secondary">A</button></a>
        <a><button class="btn rounded-5 btn-secondary">A</button></a>
        <a><button class="btn rounded-5 btn-secondary mb-2">A</button></a>
    </div>

    {{-- Vistas principales --}}
    @yield('create_token')
    @yield('home')
    @yield('pools')
    @yield('showAllTokens')
    @yield('showMyTokens')
    @yield('showTransactions')
    @yield('swap')
@endsection
