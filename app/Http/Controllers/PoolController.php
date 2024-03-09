<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Pool;
use App\Models\Liquidity;
use App\Models\Token;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PoolController extends Controller
{
    //Añadir Liquidez
    public function addLiquidity(Request $request, $userId, $poolId, $tokenId, $amount)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);
        
        $user = User::findOrFail($userId);
        $token = Token::findOrFail($tokenId);
        $pool = Pool::findOrFail($poolId);
        
        // Agregar la transacción de liquidez
        $transaction = new Transaction();
        $transaction->type = 'AgregarLiquidez';
        $transaction->user_id = $userId;
        $transaction->amount = $amount;
        $transaction->save();

        // Actualizar la tabla de liquidez
        $liquidity = new Liquidity();
        $liquidity->user_id = $userId;
        $liquidity->pool_id = $poolId;
        $liquidity->token_id = $tokenId;
        $liquidity->amount = $amount;
        $liquidity->save();

        // Actualizar la liquidez total del pool
        $pool->total_liquidity += $amount;
        $pool->save();

        return redirect()->route('addLiquiditySuccess')->with('info', 'Liquidez agregada exitosamente');
    }

    //Quitar Liquidez
    public function removeLiquidity(Request $request, $userId, $poolId, $tokenId, $amount)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        $user = User::findOrFail($userId);
        $token = Token::findOrFail($tokenId);
        $pool = Pool::findOrFail($poolId);

        $liquidity = Liquidity::where('user_id', $userId)
            ->where('pool_id', $poolId)
            ->where('token_id', $tokenId)
            ->firstOrFail();

        if ($liquidity->amount < $amount) {
            return redirect()->route('removeLiquidityError')->with('error', 'No tienes suficiente liquidez para retirar');
        }

        // Restar la cantidad de liquidez del usuario
        $liquidity->amount -= $amount;
        $liquidity->save();

        // Restar la cantidad de liquidez del pool
        $pool->total_liquidity -= $amount;
        $pool->save();

        // Agregar la transacción de liquidez
        $transaction = new Transaction();
        $transaction->type = 'RemoverLiquidez';
        $transaction->user_id = $userId;
        $transaction->amount = $amount;
        $transaction->save();

        return redirect()->route('removeLiquiditySuccess')->with('info', 'Liquidez retirada exitosamente');
    }



    public function createPool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'token1' => 'required|exists:tokens,id', // Asegúrate de que el token1 exista en la tabla de tokens
            'token2' => 'required|exists:tokens,id', // Asegúrate de que el token2 exista en la tabla de tokens
        ]);

        // Obtener el usuario autenticado
        $user = auth()->user();

        // Buscar los tokens por ID
        $token1 = Token::findOrFail($request->token1);
        $token2 = Token::findOrFail($request->token2);

        DB::beginTransaction();

        try {
            // Crear un nuevo pool con el usuario autenticado como dueño
            $pool = new Pool();
            $pool->name = $request->name;
            $pool->description = $request->description;
            $pool->total_liquidity =   0;
            $pool->user_id = $user->id;
            $pool->token1_id = $token1->id; // Guarda el ID del token1
            $pool->token2_id = $token2->id; // Guarda el ID del token2
            $pool->save();

            // Crear una nueva transacción de tipo "pool creation"
            $transaction = new Transaction();
            $transaction->type = 'Pool Creation';
            $transaction->user_id = $user->id;
            $transaction->pool_id = $pool->id;
            $transaction->status = 'completed';
            $transaction->amount = '0';
            $transaction->save();

            DB::commit();

            return redirect()->route('createPoolSuccess')->with('info', 'Pool creada exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            // Capturar y registrar el error
            Log::error('Error creating pool: ' . $e->getMessage());
            // Redirigir con un mensaje de error
            return redirect()->back()->withErrors(['message' => 'Hubo un error al crear el pool.']);
        }
    }

    //Borrar POOL
    public function deletePool($poolId)
    {
        DB::beginTransaction();

        try {
            $pool = Pool::findOrFail($poolId);
            $pool->delete();

            DB::commit();

            return redirect()->route('deletePoolSuccess')->with('info', 'Pool eliminada exitosamente');
        } catch (Exception $e) {
            DB::rollBack();

            // Capturar y registrar el error
            Log::error('Error deleting pool: ' . $e->getMessage());

            // Redirigir con un mensaje de error
            return redirect()->back()->withErrors(['message' => 'Hubo un error al eliminar el pool.']);
        }
    }

    public function showMyPools()
    {
        $user = auth()->user();
        $myPools = $user->pools->with('token1', 'token2')->get();
        $allPools = Pool::with('token1', 'token2')->get();
        return view('auth.dashboard', compact('myPools', 'allPools'));
    }


    public function showAllPools()
    {
        $allPools = Pool::all();
        return view('auth.dashboard', compact('allPools'));
    }
    public function showHomePools()
    {
        $user = auth()->user();
        $myPools = $user->pools;
        $allPools = Pool::all();
        $tokens = Token::all();

        return view('auth.dashboard', compact('myPools', 'allPools', 'tokens'));
    }




    //! FUNCIONES PARA MANEJAR VISTAS
    public function addLiquiditySuccess()
    {
        $info = session('info');
        return view('addLiquiditySuccess', compact('info'));
    }

    public function removeLiquidityError()
    {
        $error = session('error');
        return view('removeLiquidityError', compact('error'));
    }

    public function removeLiquiditySuccess()
    {
        $info = session('info');
        return view('removeLiquiditySuccess', compact('info'));
    }

    public function createPoolSuccess()
    {
        $info = session('info');
        return view('createPoolSuccess', compact('info'));
    }

    public function deletePoolSuccess()
    {
        $info = session('info');
        return view('deletePoolSuccess', compact('info'));
    }
}
