<?php

namespace App\Http\Controllers;

use App\Models\UserToken;
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
        Log::info('Iniciando proceso de añadir liquidez');
        Log::info('Datos del request:', $request->all());

        $poolId = $request->input('poolId');
        Log::info('Pool ID recibido: ' . $poolId);

        // Asegurar que el pool existe
        $pool = Pool::find($poolId);
        if (!$pool) {
            Log::error('Se recibió un ID de pool inválido: ' . $poolId);
            return redirect()->back()->withErrors(['error' => 'ID de pool inválido.']);
        }

        // Iniciar una transacción
        DB::beginTransaction();
        Log::info('Transacción iniciada');

        try {
            // Obtener el usuario y la pool
            $user = auth()->user();
            Log::info('Usuario y pool obtenidos');

            // Validar y obtener cantidades de tokens
            $token1Amount = $request->input('token1_amount');
            $token2Amount = $request->input('token2_amount');

            // Verificar que el usuario tenga suficientes tokens
            $userToken1 = $user->tokens()->where('token_id', $pool->token1_id)->first();
            $userToken2 = $user->tokens()->where('token_id', $pool->token2_id)->first();

            Log::info('User Token 1:', [$userToken1]);
            Log::info('User Token 2:', [$userToken2]);

            Log::info('Token 1 amount needed: ' . $token1Amount);
            Log::info('Token 2 amount needed: ' . $token2Amount);

            if (!$userToken1 || $userToken1->pivot->amount < $token1Amount) {
                Log::info('El usuario no tiene suficientes tokens del tipo 1');
                return redirect()->back()->withErrors(['error' => 'No tienes suficientes tokens del tipo 1 para añadir a la pool.']);
            }

            if (!$userToken2 || $userToken2->pivot->amount < $token2Amount) {
                Log::info('El usuario no tiene suficientes tokens del tipo 2');
                return redirect()->back()->withErrors(['error' => 'No tienes suficientes tokens del tipo 2 para añadir a la pool.']);
            }

            Log::info('Actualizando cantidades de tokens del usuario');

            // Actualizar cantidades de tokens del usuario
            $userToken1->pivot->amount -= $token1Amount;
            $userToken1->pivot->save();

            $userToken2->pivot->amount -= $token2Amount;
            $userToken2->pivot->save();

            Log::info('Tokens del usuario actualizados');

            Log::info('Actualizando cantidades de tokens y liquidez total de la pool');

            // Calcular la nueva liquidez total
            $newTotalLiquidity = $pool->total_liquidity + ($token1Amount) + ($token2Amount);

            // Actualizar cantidades de tokens y liquidez total de la pool
            $pool->token1_amount += $token1Amount;
            $pool->token2_amount += $token2Amount;
            $pool->total_liquidity = $newTotalLiquidity;
            $pool->save();

            Log::info('Creando registros en la tabla liquidities');

            // Crear un nuevo registro en la tabla liquidities para cada token
            Liquidity::create([
                'user_id' => $user->id,
                'pool_id' => $pool->id,
                'token_id' => $pool->token1_id,
                'amount' => $token1Amount,
            ]);

            Liquidity::create([
                'user_id' => $user->id,
                'pool_id' => $pool->id,
                'token_id' => $pool->token2_id,
                'amount' => $token2Amount,
            ]);

            Log::info('Registros de liquidez creados');

            // Si todo va bien, confirmar la transacción
            DB::commit();
            Log::info('Transacción confirmada');

            return redirect()->back()->with('success', 'Liquidez añadida exitosamente');
        } catch (Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();

            // Registrar el error
            Log::error('Error añadiendo liquidez: ' . $e->getMessage());

            // Redirigir con un mensaje de error
            return redirect()->back()->withErrors(['error' => 'Hubo un error al añadir liquidez.']);
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
        Log::info('Creando pool con datos:', $request->all());

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
        $myPools = $user->pools()->with('token1', 'token2')->get();  // Corregido
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
        $myPoolsPage = request()->query('my_pools_page', 1);
        $allPoolsPage = request()->query('all_pools_page', 1);

        $myPools = $user->pools()->paginate(5, ['*'], 'my_pools_page', $myPoolsPage);
        $allPools = Pool::whereNotIn('id', $user->pools->pluck('id'))->paginate(5, ['*'], 'all_pools_page', $allPoolsPage);
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
