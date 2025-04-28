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

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="API Endpoints pour la gestion des transactions comptables"
 * )
 */
class TransactionController extends Controller
{
    /**
     * Afficher une liste des transactions.
     *
     * @OA\Get(
     *     path="/transactions",
     *     summary="Récupérer la liste de toutes les transactions",
     *     tags={"Transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des transactions",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Transaction")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
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
     * @OA\Post(
     *     path="/transactions",
     *     summary="Créer une nouvelle transaction comptable",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "description", "compte_debit_id", "compte_credit_id", "montant"},
     *             @OA\Property(property="date", type="string", format="date", example="2023-01-15"),
     *             @OA\Property(property="description", type="string", example="Paiement facture client"),
     *             @OA\Property(property="compte_debit_id", type="integer", example=1),
     *             @OA\Property(property="compte_credit_id", type="integer", example=3),
     *             @OA\Property(property="montant", type="number", format="float", example=1500.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données invalides",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Une erreur est survenue lors de la création de la transaction"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
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
     * @OA\Get(
     *     path="/transactions/{id}",
     *     summary="Récupérer les détails d'une transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Transaction non trouvée")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
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

    /**
     * Supprimer une transaction spécifique.
     *
     * @OA\Delete(
     *     path="/transactions/{id}",
     *     summary="Supprimer une transaction comptable",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Transaction supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Transaction non trouvée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Une erreur est survenue lors de la suppression de la transaction"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['compteDebit', 'compteCredit', 'entreeJournal'])->find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction non trouvée'
                ], 404);
            }

            // Annuler les effets de la transaction sur les soldes des comptes
            $compteDebit = $transaction->compteDebit;
            $compteCredit = $transaction->compteCredit;
            $montant = $transaction->montant;

            // Inverser les effets sur le compte débité
            if ($compteDebit->type == Compte::TYPE_ACTIF || $compteDebit->type == Compte::TYPE_CHARGE) {
                $compteDebit->solde -= $montant;
            } else {
                $compteDebit->solde += $montant;
            }

            // Inverser les effets sur le compte crédité
            if ($compteCredit->type == Compte::TYPE_PASSIF || $compteCredit->type == Compte::TYPE_PRODUIT) {
                $compteCredit->solde -= $montant;
            } else {
                $compteCredit->solde += $montant;
            }

            $compteDebit->save();
            $compteCredit->save();

            // Supprimer l'entrée du journal
            if ($transaction->entreeJournal) {
                $transaction->entreeJournal->delete();
            }

            // Supprimer la transaction
            $transaction->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de la transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
