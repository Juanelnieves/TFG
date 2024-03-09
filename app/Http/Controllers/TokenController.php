<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Termwind\Components\Dd;

class TokenController extends Controller
{
    public function createToken(Request $request, $userId)
    {
        try {
            // Validar los datos del request
            $request->validate([
                'name' => 'required|string|max:50',
                //ejemplo btc bitcoin
                'symbol' => 'required|string|max:10',
                // cantidad total de tokens creados
                'totalSupply' => 'required|numeric|gt:0',
                // url (web) para la img del token
                'url' => 'required|url',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
        try {
            // Iniciar una transacción de base de datos
            DB::beginTransaction();
            // Crear y guardar el token
            $token = new Token();

            $token->name = $request->name;
            $token->symbol = $request->symbol;
            $token->total_supply = $request->totalSupply;
            $token->url = $request->url; // link a la imagen por url
            $token->user_id = $userId;
            $token->save();

            DB::commit();
            return redirect()->route('create.tokenTransaction', ['totalSupply' => $request->totalSupply]);
        } catch (\Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();

            //Redireccionar a la vista de error con los errores de validación
            return back()->withErrors(['general' => 'Error al crear el token' . $e->getMessage()]);
        }
    }

    public function createTokenTransaction($total_supply)
    {
        try {
            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            // Crear y guardar la transacción
            $transaction = new Transaction();
            $transaction->type = "token creation";
            $transaction->status = "completed";
            $transaction->amount = $total_supply;
            $transaction->user_id = auth()->id();
            $transaction->save();

            // Confirmar la transacción si todo salió bien
            DB::commit();
            return redirect()->route('showAll.tokens')->with('success', 'Transacción creada exitosamente');
        } catch (\Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();
            dd($total_supply);
            // Redireccionar a la vista de error con los errores de validación
            return redirect()->route('showCreate.token')->withErrors(['general' => 'Error al crear la transacción' . $e->getMessage()]);
        }
    }

    public function showCreateToken()
    {
        // Obtén el ID del usuario de la sesión o de otra fuente
        $userId = auth()->id();
        // Pasa el ID del usuario a la vista
        return view('project_views.createToken', compact('userId'));
    }

    public function showMyTokens()
    {
        // Obtén el ID del usuario de la sesión
        $userId = auth()->id();
        // Recupera todos los tokens del usuario
        $tokens = Token::where('user_id', $userId)->get();
        // Pasa los tokens a la vista
        return view('project_views.showMyTokens', compact('tokens'));
    }

    public function showAllTokens()
    {
        // Recupera todos los tokens de la base de datos
        $tokens = Token::all();

        // Pasa los tokens a la vista
        return view('project_views.showAllTokens', compact('tokens'));
    }

    public function getTokens()
    {
        $tokens = Token::all();
        return response()->json($tokens);
    }

    public function showSwapView()
    {
        // Obtener todos los tokens para pasarlos a la vista
        $tokens = Token::pluck('name');

        // dd($tokens);

        return view('project_views.swap', compact('tokens'));
    }

}
