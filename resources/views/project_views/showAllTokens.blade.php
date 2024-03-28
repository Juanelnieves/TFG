@extends('layouts.general')

@section('showAllTokens')
    <div class="container mx-auto mt-5">
        <h3 class="text-xl font-bold mb-4">Todos los Tokens</h3>
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
