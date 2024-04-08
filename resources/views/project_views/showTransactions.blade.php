@extends('layouts.general')

@section('showTransactions')
    <div class="container mx-auto mt-5">
        <h1 class="text-center pb-1"><span class="rounded-pill title-custom px-3 display-6 pb-5">Transacciones</span></h1>
        <div class="flex justify-center overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-yellow-500">
                    <th class="px-6 py-3 text-left text-base my-custom-class text-white uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-base my-custom-class text-white uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-base my-custom-class text-white uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-base my-custom-class text-white uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-3 text-left text-base my-custom-class text-white uppercase tracking-wider">id del Usuario
                    </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($transactions as $transaction)
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'table-row-even' : 'table-row-odd' }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->user->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
