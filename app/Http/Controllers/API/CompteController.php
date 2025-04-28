<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompteController extends Controller
{
    /**
     * Afficher une liste des comptes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comptes = Compte::all();
        return response()->json([
            'success' => true,
            'data' => $comptes
        ]);
    }

    /**
     * Enregistrer un nouveau compte.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:comptes',
            'type' => 'required|in:actif,passif,produit,charge',
            'solde' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $compte = Compte::create([
            'nom' => $request->nom,
            'code' => $request->code,
            'type' => $request->type,
            'solde' => $request->solde ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => $compte
        ], 201);
    }

    /**
     * Afficher les détails d'un compte spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compte = Compte::find($id);
        
        if (!$compte) {
            return response()->json([
                'success' => false,
                'message' => 'Compte non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $compte
        ]);
    }

    /**
     * Mettre à jour un compte spécifique.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $compte = Compte::find($id);
        
        if (!$compte) {
            return response()->json([
                'success' => false,
                'message' => 'Compte non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:comptes,code,' . $id,
            'type' => 'required|in:actif,passif,produit,charge',
            'solde' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $compte->update([
            'nom' => $request->nom,
            'code' => $request->code,
            'type' => $request->type,
            'solde' => $request->solde ?? $compte->solde,
        ]);

        return response()->json([
            'success' => true,
            'data' => $compte
        ]);
    }

    /**
     * Supprimer un compte spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $compte = Compte::find($id);
        
        if (!$compte) {
            return response()->json([
                'success' => false,
                'message' => 'Compte non trouvé'
            ], 404);
        }

        // Vérifier si le compte est utilisé dans des transactions
        $transactionsCount = $compte->transactionsDebit()->count() + $compte->transactionsCredit()->count();
        
        if ($transactionsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce compte car il est utilisé dans des transactions'
            ], 400);
        }

        $compte->delete();

        return response()->json([
            'success' => true,
            'message' => 'Compte supprimé avec succès'
        ]);
    }
}
