@extends('layouts.template')

@section('general')
  {{-- SIDEBAR --}}
  <div class="col-1 bg-dark rounded-5 ms-2 d-grid justify-content-center align-items-center">
    <div></div>
    <a class="btn rounded-5 btn-lg btn-secondary mt-5" href="{{ url('/pools') }}">
        <i class="fa-solid fa-person-swimming"></i>
    </a>
    <a class="btn rounded-5 btn-lg btn-secondary" href="{{ url('/tokens/all') }}">
        <i class="fa-brands fa-bitcoin"></i>
    </a>
    <a class="btn rounded-5 btn-lg btn-secondary" href="{{ url('/tokens/view') }}">
        <i class="fa-solid fa-coins"></i>
    </a>
    <a class="btn rounded-5 btn-lg btn-secondary" href="{{ url('/transactions/all') }}">
        <i class="fa-solid fa-receipt"></i>
    </a>
    <a class="btn rounded-5 btn-lg btn-secondary mb-5" href="{{ url('/swap') }}">
        <i class="fa-solid fa-retweet"></i>        </a>
    <div></div>
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
