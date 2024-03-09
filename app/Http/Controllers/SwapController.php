<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token; 

class SwapController extends Controller
{
    public function showSwapView()
    {
        // Obtener todos los tokens para pasarlos a la vista
        $tokens = Token::all();

        // dd($tokens);

        return view('swap', compact('tokens'));
    }

    public function exchangeTokens(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'token1' => 'required|exists:tokens,id',
            'token2' => 'required|exists:tokens,id',
        ]);

        // Obtener los IDs de los tokens seleccionados
        $token1Id = $request->input('token1');
        $token2Id = $request->input('token2');

        // Lógica de intercambio (puedes ajustar esto según tus necesidades)
        // ...

        // Redireccionar de nuevo a la vista de intercambio con un mensaje
        return redirect()->route('swap.exchange')->with('success', 'Intercambio de tokens exitoso');
    }
}
