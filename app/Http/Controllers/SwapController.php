<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;


class SwapController extends Controller
{
    public function showSwap(Request $request)
    {
        $tokens = Token::pluck('name');
        $selectedToken = $request->input('token'); // Obtener el token seleccionado desde la solicitud

        // Verificar si se seleccionó un token y obtener el modelo correspondiente
        $tokenModel = $selectedToken ? Token::where('name', $selectedToken)->first() : null;

        return view('project_views.swap', compact('tokens', 'tokenModel'));
    }
}
