<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pool;
use App\Models\Token;
use App\Models\Transaction;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SwapController extends Controller
{
    public function showSwap(Request $request)
    {
        $tokens = Token::all();
        $selectedToken1 = $request->input('token1');
        $selectedToken2 = $request->input('token2');
        Log::info('Selected Token 1 in showSwap:', [$selectedToken1]);
        Log::info('Selected Token 2 in showSwap:', [$selectedToken2]);

        $tokenModel1 = null;
        $tokenModel2 = null;
        $pool = null;
        $userToken1Amount = 0;
        $userToken2Amount = 0;
        Log::info('Selected Token 1:', [$selectedToken1]);
        Log::info('Selected Token 2:', [$selectedToken2]);

        if ($selectedToken1 && $selectedToken2) {
            $tokenModel1 = Token::where('name', $selectedToken1)->first();
            $tokenModel2 = Token::where('name', $selectedToken2)->first();

            Log::info('Token Model 1:', [$tokenModel1]);
            Log::info('Token Model 2:', [$tokenModel2]);

            if ($tokenModel1 && $tokenModel2) {
                $pool = Pool::where(function ($query) use ($tokenModel1, $tokenModel2) {
                    $query->where('token1_id', $tokenModel1->id)
                        ->where('token2_id', $tokenModel2->id);
                })->orWhere(function ($query) use ($tokenModel1, $tokenModel2) {
                    $query->where('token1_id', $tokenModel2->id)
                        ->where('token2_id', $tokenModel1->id);
                })->first();

                Log::info('Pool:', [$pool]);

                // Asegurarse de que el usuario está autenticado antes de intentar obtener sus tokens
                if (auth()->check()) {
                    $userToken1 = UserToken::where('user_id', auth()->user()->id)
                        ->where('token_id', $tokenModel1->id)
                        ->first();
                    $userToken2 = UserToken::where('user_id', auth()->user()->id)
                        ->where('token_id', $tokenModel2->id)
                        ->first();

                    Log::info('User Token 1:', [$userToken1]);
                    Log::info('User Token 2:', [$userToken2]);

                    $userToken1Amount = $userToken1 ? $userToken1->amount : 0;
                    $userToken2Amount = $userToken2 ? $userToken2->amount : 0;

                    Log::info('User Token 1 Amount:', [$userToken1Amount]);
                    Log::info('User Token 2 Amount:', [$userToken2Amount]);
                }
            }
        }
        Log::info('User Token 1 Amount in showSwap2:', [$userToken1Amount]);
        Log::info('User Token 2 Amount in showSwap2:', [$userToken2Amount]);

        return view('project_views.swap', compact('tokens', 'selectedToken1', 'selectedToken2', 'pool', 'userToken1Amount', 'userToken2Amount'));
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
            return back()->withErrors(['error' => 'No existe un pool con los tokens seleccionados.'])
                ->with([
                    'token1' => $token1->name,
                    'token2' => $token2->name,
                ]);
        }

        // Obtener la cantidad de token1 que el usuario desea intercambiar
        $amountToSwap = $request->amount;

        // Calcular la cantidad de token2 que el usuario recibirá
        // Asumiendo una relación de liquidez simple (esto puede variar dependiendo de la lógica de negocio específica)
        $amountToReceive = $amountToSwap * ($pool->token2_amount / $pool->token1_amount);

        // Verificar si el pool tiene suficientes token2 para el intercambio
        if ($pool->token2_amount < $amountToReceive) {
            return back()->withErrors(['error' => 'No hay suficientes tokens disponibles en el pool para realizar el intercambio.'])
                ->with([
                    'amountToSwap' => $amountToSwap,
                    'token1' => $token1->name,
                    'token2' => $token2->name,
                ]);
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
            'type' => 'swap', 
            'status' => 'completed', 
            'user_id' => auth()->user()->id, 
            'amount' => $amountToSwap,
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
        Log::info('Request received', $request->all());

        $request->validate([
            'token1' => 'required|exists:tokens,name',
            'token2' => 'required|exists:tokens,name',
        ]);

        $token1Name = $request->query('token1');
        $token2Name = $request->query('token2');

        $token1Model = Token::where('name', $token1Name)->first();
        $token2Model = Token::where('name', $token2Name)->first();

        if (!$token1Model || !$token2Model) {
            return response()->json(['error' => 'Uno de los tokens no existe.'], 404);
        }

        Log::info('Attempting to find pool for tokens', ['token1' => $token1Name, 'token2' => $token2Name]);

        $pool = Pool::where(function ($query) use ($token1Model, $token2Model) {
            $query->where('token1_id', $token1Model->id)
                ->where('token2_id', $token2Model->id);
        })->orWhere(function ($query) use ($token1Model, $token2Model) {
            $query->where('token1_id', $token2Model->id)
                ->where('token2_id', $token1Model->id);
        })->first();

        if (!$pool) {
            Log::info('Pool not found for tokens', ['token1' => $token1Name, 'token2' => $token2Name]);
            return response()->json(['error' => 'No existe un pool con los tokens seleccionados.'], 404);
        }

        Log::info('Pool found', $pool->toArray());

        $token1Amount = $pool->token1_amount;
        $token2Amount = $pool->token2_amount;

        if ($token1Amount == 0 || $token2Amount == 0) {
            Log::info('One of the tokens in the pool has a zero amount', ['pool' => $pool->toArray()]);
            return response()->json(['error' => 'Uno de los tokens en el pool tiene una cantidad de cero.'], 400);
        }

        try {
            $rate = $token2Amount / $token1Amount;
            Log::info('Swap rate calculated', ['rate' => $rate]);

            return response()->json(['rate' => $rate]);
        } catch (\DivisionByZeroError $e) {
            Log::error('Division by zero error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'La cantidad de token1 en el pool es cero.'], 400);
        }
    }

    public function getUserTokenAmounts(Request $request)
{
    $selectedToken1 = $request->input('token1');
    $selectedToken2 = $request->input('token2');

    $userToken1Amount = 0;
    $userToken2Amount = 0;

    if ($selectedToken1 && $selectedToken2) {
        $tokenModel1 = Token::where('name', $selectedToken1)->first();
        $tokenModel2 = Token::where('name', $selectedToken2)->first();

        if ($tokenModel1 && $tokenModel2) {
            $userToken1 = UserToken::where('user_id', auth()->user()->id)
                ->where('token_id', $tokenModel1->id)
                ->first();
            $userToken2 = UserToken::where('user_id', auth()->user()->id)
                ->where('token_id', $tokenModel2->id)
                ->first();

            $userToken1Amount = $userToken1 ? $userToken1->amount : 0;
            $userToken2Amount = $userToken2 ? $userToken2->amount : 0;
        }
    }

    return response()->json([
        'userToken1Amount' => $userToken1Amount,
        'userToken2Amount' => $userToken2Amount,
    ]);
}
}
