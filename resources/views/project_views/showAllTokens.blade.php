@extends('layouts.general')

@section('showAllTokens')
    <div class="container mx-auto mt-5">
        <h1 class="text-center pb-1"><span class="rounded-pill title-custom px-3 display-6 pb-5">Todos los tokens</span></h1>
        <div class="flex justify-center overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-yellow-500">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ID del
                            Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Símbolo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider text-center">
                            Suministro
                            Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($tokens as $token)
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'table-row-even' : 'table-row-odd' }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $token->user_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $token->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $token->symbol }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $token->total_supply }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
