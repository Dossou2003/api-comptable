<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\EntreeJournal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Afficher une liste des transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with(['compteDebit', 'compteCredit', 'entreeJournal.utilisateur'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Enregistrer une nouvelle transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'compte_debit_id' => 'required|exists:comptes,id',
            'compte_credit_id' => 'required|exists:comptes,id|different:compte_debit_id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Créer la transaction
            $transaction = Transaction::create([
                'date' => $request->date,
                'description' => $request->description,
                'compte_debit_id' => $request->compte_debit_id,
                'compte_credit_id' => $request->compte_credit_id,
                'montant' => $request->montant,
            ]);

            // Créer l'entrée de journal
            EntreeJournal::create([
                'transaction_id' => $transaction->id,
                'utilisateur_id' => Auth::id(),
            ]);

            // Mettre à jour les soldes des comptes
            $compteDebit = Compte::find($request->compte_debit_id);
            $compteCredit = Compte::find($request->compte_credit_id);

            // Pour un compte d'actif ou de charge, un débit augmente le solde
            if ($compteDebit->type == Compte::TYPE_ACTIF || $compteDebit->type == Compte::TYPE_CHARGE) {
                $compteDebit->solde += $request->montant;
            } else {
                $compteDebit->solde -= $request->montant;
            }

            // Pour un compte de passif ou de produit, un crédit augmente le solde
            if ($compteCredit->type == Compte::TYPE_PASSIF || $compteCredit->type == Compte::TYPE_PRODUIT) {
                $compteCredit->solde += $request->montant;
            } else {
                $compteCredit->solde -= $request->montant;
            }

            $compteDebit->save();
            $compteCredit->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $transaction->load(['compteDebit', 'compteCredit', 'entreeJournal.utilisateur'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de la transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les détails d'une transaction spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with(['compteDebit', 'compteCredit', 'entreeJournal.utilisateur'])->find($id);
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
}
