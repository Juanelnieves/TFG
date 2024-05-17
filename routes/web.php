<?php

use App\Http\Controllers\CryptoController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SwapController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Inicio
Route::get('/', function () {
    return redirect()->route('login');
});

//rutas para pools
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cryptos', [CryptoController::class, 'index'])->name('cryptos.index');
    Route::get('/pools', [PoolController::class, 'showAllPools'])->name('pools.showAll');
    Route::get('/mypools', [PoolController::class, 'showMyPools'])->name('pools.showMy');

    Route::post('/addLiquidity', [PoolController::class, 'addLiquidity'])->name('addLiquidity');
    Route::post('/removeLiquidity', [PoolController::class, 'removeLiquidity'])->name('removeLiquidity');
    Route::post('/createPool', [PoolController::class, 'createPool'])->name('createPool');
    Route::delete('/deletePool/{poolId}', [PoolController::class, 'deletePool'])->name('deletePool');
    //rutas tokens
    Route::post('/tokens/create/{userId}', [TokenController::class, 'createToken'])->name('create.token');
    Route::get('/tokens/creation', [TokenController::class, 'showCreateToken'])->name('showCreate.token');
    //transacción de crear tokens
    Route::get('/tokens/createTransaction/{totalSupply}', [TokenController::class, 'createTokenTransaction'])->name('create.tokenTransaction');
    Route::get('/tokens/view', [TokenController::class, 'showMyTokens'])->name('showMy.tokens');
    Route::get('/tokens/all', [TokenController::class, 'showAllTokens'])->name('showAll.tokens');
    // tabla de todas las transacciones
    Route::get('/transactions/all', [TransactionController::class, 'showAllTransactions'])->name('showAll.transactions');
    Route::post('/swap/tokens', [SwapController::class, 'swapTokens'])->name('swap.tokens');
    Route::get('/swap', [SwapController::class, 'showSwap'])->name('swap');
    Route::get('/swap/user-token-amounts', [SwapController::class, 'getUserTokenAmounts'])->name('swap.userTokenAmounts');
});


// Inicio tras login
Route::get('/home', function () {
    return view('auth.dashboard');
})->middleware(['auth', 'verified']);

Route::get('/api/swap/rate', [SwapController::class, 'getSwapRate'])->name('api.swap.rate');
Route::get('/api/tokens/{tokenName}', [TokenController::class, 'getTokenInfo']);

Route::get('/home', [PoolController::class, 'showHomePools'])->middleware(['auth', 'verified'])->name('home');

// Rutas que acceden solo desde login
Route::prefix('')->middleware('auth', 'verified')->group(function () {
});

Route::post('/tokens/create', [TokenController::class, 'createToken'])->name('createToken');
Route::post('/swap/tokenType', [SwapController::class, 'cambiarTipoToken'])->name('tokenType');
