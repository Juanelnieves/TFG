<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function showAllTransactions()
    {
        // Recupera todas las transacciones de la base de datos
        $transactions = Transaction::all();

        // Pasa las transacciones a la vista
        return view('project_views.showTransactions', compact('transactions'));
    }

    public function showMyTransactions()
{
    // Obtén el ID del usuario autenticado
    $userId = auth()->id();

    // Recupera todas las transacciones del usuario autenticado
    $transactions = Transaction::where('user_id', $userId)->get();

    // Pasa las transacciones a la vista
    return view('project_views.showTransactions', compact('transactions'));
}

}
