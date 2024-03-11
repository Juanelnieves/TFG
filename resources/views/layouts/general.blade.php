@extends('layouts.template')

@section('general')

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
