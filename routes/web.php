<?php

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
    return view('welcome');
});

//rutas para pools
Route::middleware(['auth', 'verified'])->group(function () {
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

    //Rutas Swap
    Route::get('/swap', [TokenController::class, 'showSwapView'])->name('swap.view');
    Route::post('/swap', [TokenController::class, 'exchangeTokens'])->name('swap.exchange');
    
});


// Inicio tras login
Route::get('/home', function () {
    return view('auth.dashboard');
})->middleware(['auth', 'verified']);


Route::get('/home', [PoolController::class, 'showHomePools'])->middleware(['auth', 'verified']);

// Rutas que acceden solo desde login
Route::prefix('')->middleware('auth', 'verified')->group(function () {
});

Route::post('/tokens/create', [TokenController::class, 'createToken'])->name('createToken');

