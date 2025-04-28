<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Comptes",
 *     description="API Endpoints pour la gestion des comptes comptables"
 * )
 */
class CompteController extends Controller
{
    /**
     * Afficher une liste des comptes.
     *
     * @OA\Get(
     *     path="/comptes",
     *     summary="Récupérer la liste de tous les comptes comptables",
     *     tags={"Comptes"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des comptes comptables",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Compte")
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
        $comptes = Compte::all();
        return response()->json([
            'success' => true,
            'data' => $comptes
        ]);
    }

    /**
     * Enregistrer un nouveau compte.
     *
     * @OA\Post(
     *     path="/comptes",
     *     summary="Créer un nouveau compte comptable",
     *     tags={"Comptes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "code", "type"},
     *             @OA\Property(property="nom", type="string", example="Banque XYZ"),
     *             @OA\Property(property="code", type="string", example="512100"),
     *             @OA\Property(property="type", type="string", enum={"actif", "passif", "produit", "charge"}, example="actif"),
     *             @OA\Property(property="solde", type="number", format="float", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compte créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Compte")
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
     *     security={{"bearerAuth":{}}}
     * )
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
     * @OA\Get(
     *     path="/comptes/{id}",
     *     summary="Récupérer les détails d'un compte comptable",
     *     tags={"Comptes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du compte",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du compte",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Compte")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Compte non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Compte non trouvé")
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
     * @OA\Put(
     *     path="/comptes/{id}",
     *     summary="Mettre à jour un compte comptable",
     *     tags={"Comptes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du compte",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom", "code", "type"},
     *             @OA\Property(property="nom", type="string", example="Banque XYZ"),
     *             @OA\Property(property="code", type="string", example="512100"),
     *             @OA\Property(property="type", type="string", enum={"actif", "passif", "produit", "charge"}, example="actif"),
     *             @OA\Property(property="solde", type="number", format="float", example=1000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compte mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Compte")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Compte non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Compte non trouvé")
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
     *     security={{"bearerAuth":{}}}
     * )
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
     * @OA\Delete(
     *     path="/comptes/{id}",
     *     summary="Supprimer un compte comptable",
     *     tags={"Comptes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du compte",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compte supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Compte supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Impossible de supprimer le compte",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Impossible de supprimer ce compte car il est utilisé dans des transactions")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Compte non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Compte non trouvé")
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
