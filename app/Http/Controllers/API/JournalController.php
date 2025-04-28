<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EntreeJournal;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Journal",
 *     description="API Endpoints pour le journal comptable"
 * )
 */
class JournalController extends Controller
{
    /**
     * Afficher l'historique des écritures.
     *
     * @OA\Get(
     *     path="/journal",
     *     summary="Récupérer l'historique des écritures comptables",
     *     tags={"Journal"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des entrées du journal",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EntreeJournal")
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
        $entrees = EntreeJournal::with(['transaction.compteDebit', 'transaction.compteCredit', 'utilisateur'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entrees
        ]);
    }
}
