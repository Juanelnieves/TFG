<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pool;
use App\Models\Token;
use App\Models\Transaction;
use App\Models\UserToken;
use Illuminate\Http\Request;


class SwapController extends Controller
{
    // En SwapController.php

    public function showSwap(Request $request)
    {
        $tokens = Token::pluck('name');
        $selectedToken1 = $request->input('token1'); // Obtener el token seleccionado desde la solicitud
        $selectedToken2 = $request->input('token2'); // Obtener el segundo token seleccionado desde la solicitud

        // Verificar si se seleccionaron tokens y obtener los modelos correspondientes
        $tokenModel1 = $selectedToken1 ? Token::where('name', $selectedToken1)->first() : null;
        $tokenModel2 = $selectedToken2 ? Token::where('name', $selectedToken2)->first() : null;

        // Verificar si existe un pool con los tokens seleccionados
        $pool = Pool::where('token1_id', $tokenModel1->id)
            ->where('token2_id', $tokenModel2->id)
            ->orWhere(function ($query) use ($tokenModel1, $tokenModel2) {
                $query->where('token1_id', $tokenModel2->id)
                    ->where('token2_id', $tokenModel1->id);
            })
            ->first();

        return view('project_views.swap', compact('tokens', 'tokenModel1', 'tokenModel2', 'pool'));
    }
    public function swapTokens(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'token1' => 'required|exists:tokens,name',
            'token2' => 'required|exists:tokens,name',
            'amount' => 'required|numeric|min:1',
        ]);

        // Obtener los tokens seleccionados
        $token1 = Token::where('name', $request->token1)->first();
        $token2 = Token::where('name', $request->token2)->first();

        // Verificar si existe un pool con los tokens seleccionados
        $pool = Pool::where('token1_id', $token1->id)
            ->where('token2_id', $token2->id)
            ->orWhere(function ($query) use ($token1, $token2) {
                $query->where('token1_id', $token2->id)
                    ->where('token2_id', $token1->id);
            })
            ->first();

        if (!$pool) {
            return back()->withErrors(['No existe un pool con los tokens seleccionados.']);
        }

        // Obtener la cantidad de token1 que el usuario desea intercambiar
        $amountToSwap = $request->amount;

        // Calcular la cantidad de token2 que el usuario recibirá
        // Asumiendo una relación de liquidez simple (esto puede variar dependiendo de la lógica de negocio específica)
        $amountToReceive = $amountToSwap * ($pool->token2_amount / $pool->token1_amount);

        // Verificar si el pool tiene suficientes token2 para el intercambio
        if ($pool->token2_amount < $amountToReceive) {
            return back()->withErrors(['No hay suficientes tokens disponibles en el pool para realizar el intercambio.']);
        }

        // Actualizar las cantidades de tokens en el pool
        $pool->token1_amount -= $amountToSwap;
        $pool->token2_amount += $amountToReceive;
        $pool->save();

        // Actualizar las cantidades de tokens en la cuenta del usuario
        // Asumiendo que el usuario ya tiene token1 y necesita actualizar su cantidad de token2
        $userToken1 = UserToken::where('user_id', auth()->user()->id)
            ->where('token_id', $token1->id)
            ->first();
        $userToken1->amount -= $amountToSwap;
        $userToken1->save();

        $userToken2 = UserToken::where('user_id', auth()->user()->id)
            ->where('token_id', $token2->id)
            ->first();
        if (!$userToken2) {
            // Si el usuario no tiene token2, crear un nuevo registro
            $userToken2 = new UserToken([
                'user_id' => auth()->user()->id,
                'token_id' => $token2->id,
                'amount' => $amountToReceive,
            ]);
            $userToken2->save();
        } else {
            // Si el usuario ya tiene token2, actualizar la cantidad
            $userToken2->amount += $amountToReceive;
            $userToken2->save();
        }

        // Después de realizar el intercambio, crear una nueva transacción
        $transaction = new Transaction([
            'type' => 'swap', // Asumiendo que 'swap' es un tipo de transacción válido
            'status' => 'completed', // Asumiendo que la transacción se completa exitosamente
            'user_id' => auth()->user()->id, // Asumiendo que el usuario está autenticado
            'amount' => $amountToSwap, // La cantidad de token1 que se intercambia
        ]);
        $transaction->save();
        
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->back()->with([
            'success' => 'Intercambio realizado con éxito.',
            'amountToSwap' => $amountToSwap,
            'amountToReceive' => $amountToReceive,
            'token1' => $token1->name,
            'token2' => $token2->name,
        ]);
    }


    public function getSwapRate(Request $request)
    {
        // Validar los parámetros de la solicitud
        $request->validate([
            'token1' => 'required|exists:tokens,name',
            'token2' => 'required|exists:tokens,name',
        ]);

        // Obtener los tokens seleccionados
        $token1 = Token::where('name', $request->query('token1'))->first();
        $token2 = Token::where('name', $request->query('token2'))->first();


        $token1Model = Token::where('name', $token1)->first();
        $token2Model = Token::where('name', $token2)->first();

        if (!$token1Model || !$token2Model) {
            return response()->json(['error' => 'Uno de los tokens no existe.'], 404);
        }

        // Verificar si existe un pool con los tokens seleccionados
        $pool = Pool::where(function ($query) use ($token1Model, $token2Model) {
            $query->where('token1_id', $token1Model->id)
                ->where('token2_id', $token2Model->id);
        })->orWhere(function ($query) use ($token1Model, $token2Model) {
            $query->where('token1_id', $token2Model->id)
                ->where('token2_id', $token1Model->id);
        })->first();


        if (!$pool) {
            return response()->json(['error' => 'No existe un pool con los tokens seleccionados.'], 404);
        }

        // Calcular la tasa de intercambio
        // Asumiendo una relación de liquidez simple (esto puede variar dependiendo de la lógica de negocio específica)
        $rate = $pool->token2_amount / $pool->token1_amount;

        return response()->json(['rate' => $rate]);
    }
}
