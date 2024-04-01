@extends('auth.template')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Top Cryptos</h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Price (USD)</th>
                        <th>Market Cap (USD)</th>
                        <th>Market Cap Rank</th>
                        <th>High 24h (USD)</th>
                        <th>Low 24h (USD)</th>
                        <th>ATH (USD)</th>
                        <th>ATH Change %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cryptos as $crypto)
                        <tr>
                            <td><img src="{{ $crypto['image'] }}" alt="{{ $crypto['name'] }}" class="img-fluid"></td>
                            <td>{{ $crypto['name'] }}</td>
                            <td>{{ strtoupper($crypto['symbol']) }}</td>
                            <td>{{ $crypto['current_price'] }}</td>
                            <td>{{ $crypto['market_cap'] }}</td>
                            <td>{{ $crypto['market_cap_rank'] }}</td>
                            <td>{{ $crypto['high_24h'] }}</td>
                            <td>{{ $crypto['low_24h'] }}</td>
                            <td>{{ $crypto['ath'] }}</td>
                            <td>{{ $crypto['ath_change_percentage'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
