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
use App\Services\CryptoPriceService;

class PoolController extends Controller
{
    //Añadir Liquidez
    public function addLiquidity(Request $request)
    {
        // Validar los datos del request
        $request->validate([
            'userId' => 'required|numeric|exists:users,id',
            'poolId' => 'required|numeric|exists:pools,id',
            'token1_amount' => 'required|numeric|gt:0',
            'token2_amount' => 'required|numeric|gt:0',
        ]);

        // Iniciar una transacción
        DB::beginTransaction();

        try {
            // Obtener el usuario y la pool
            $user = User::findOrFail($request->userId);
            $pool = Pool::findOrFail($request->poolId);

            // Verificar que el usuario tenga suficientes tokens
            $userToken1Balance = $user->tokens()->where('token_id', $pool->token1_id)->first()->pivot->amount ?? 0;
            $userToken2Balance = $user->tokens()->where('token_id', $pool->token2_id)->first()->pivot->amount ?? 0;

            if ($userToken1Balance < $request->token1_amount || $userToken2Balance < $request->token2_amount) {
                throw new Exception('No tienes suficientes tokens para añadir a la pool.');
            }

            // Crear un nuevo registro en la tabla liquiditys para cada token
            $liquidity1 = new Liquidity();
            $liquidity1->user_id = $request->userId;
            $liquidity1->pool_id = $request->poolId;
            $liquidity1->token_id = $pool->token1_id;
            $liquidity1->amount = $request->token1_amount;
            $liquidity1->save();

            $liquidity2 = new Liquidity();
            $liquidity2->user_id = $request->userId;
            $liquidity2->pool_id = $request->poolId;
            $liquidity2->token_id = $pool->token2_id;
            $liquidity2->amount = $request->token2_amount;
            $liquidity2->save();

            // Actualizar la liquidez total de la pool
            $pool->total_liquidity += $request->token1_amount + $request->token2_amount;
            $pool->save();

            // Reducir la cantidad de tokens del usuario
            $user->tokens()->updateExistingPivot($pool->token1_id, ['amount' => $userToken1Balance - $request->token1_amount]);
            $user->tokens()->updateExistingPivot($pool->token2_id, ['amount' => $userToken2Balance - $request->token2_amount]);

            // Si todo va bien, confirmar la transacción
            DB::commit();

            return redirect()->back()->with('success', 'Liquidez añadida exitosamente');
        } catch (Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();

            // Registrar el error
            Log::error('Error añadiendo liquidez: ' . $e->getMessage());

            // Redirigir con un mensaje de error
            return redirect()->back()->withErrors(['message' => 'Hubo un error al añadir liquidez.']);
        }
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

    public function showHomePools(CryptoPriceService $cryptoPriceService)
    {
        $user = auth()->user();
        $myPools = $user->pools;
        $allPools = Pool::all();
        $tokens = Token::all();

        // Obtener el volumen en 24 horas
        $totalVolume = $cryptoPriceService->get24hVolume();

        // Obtener el marketCap
        $marketCap = $cryptoPriceService->getBTCMarketCap();

        // Obtener el precio del token GAIA proporcional al precio de BTC
        $gaiaPrice = 1000000 / ($cryptoPriceService->getTokenPrice('bitcoin'));
        $gaiaPrice = number_format($gaiaPrice, 3); // Trunca a 3 decimales

        return view('auth.dashboard', compact('user', 'myPools', 'allPools', 'tokens', 'totalVolume', 'gaiaPrice', 'marketCap'));
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
