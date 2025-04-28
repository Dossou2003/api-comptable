<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompteController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\JournalController;
use App\Http\Controllers\API\BalanceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour les comptes comptables
Route::apiResource('comptes', CompteController::class);

// Routes pour les transactions
Route::apiResource('transactions', TransactionController::class)->except(['update', 'destroy']);

// Route pour le journal comptable
Route::get('journal', [JournalController::class, 'index']);

// Route pour l'export de la balance comptable
Route::get('export-balance', [BalanceController::class, 'exportExcel']);
