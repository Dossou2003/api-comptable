<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompteController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\JournalController;
use App\Http\Controllers\API\BalanceController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes publiques
Route::post('login', [AuthController::class, 'login']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Informations utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Routes pour les comptes comptables
    Route::apiResource('comptes', CompteController::class);

    // Routes pour les transactions (seuls les comptables peuvent créer des transactions)
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{id}', [TransactionController::class, 'show']);
    Route::post('transactions', [TransactionController::class, 'store'])->middleware('role:comptable');
    Route::delete('transactions/{id}', [TransactionController::class, 'destroy'])->middleware('role:admin');

    // Route pour le journal comptable
    Route::get('journal', [JournalController::class, 'index']);

    // Routes pour l'export de la balance comptable
    Route::get('export-balance', [BalanceController::class, 'exportExcel']);
    Route::get('export-balance/csv', [BalanceController::class, 'exportCsv']);
});
