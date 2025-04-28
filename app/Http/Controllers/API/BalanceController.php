<?php

namespace App\Http\Controllers\API;

use App\Exports\BalanceExport;
use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Balance",
 *     description="API Endpoints pour la balance comptable"
 * )
 */
class BalanceController extends Controller
{
    /**
     * Générer un fichier Excel avec la balance comptable.
     *
     * @OA\Get(
     *     path="/export-balance",
     *     summary="Exporter la balance comptable au format Excel",
     *     tags={"Balance"},
     *     @OA\Response(
     *         response=200,
     *         description="Fichier HTML permettant de télécharger la balance en Excel",
     *         @OA\MediaType(
     *             mediaType="text/html"
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        // Utiliser la classe d'export
        $export = new BalanceExport();
        $htmlContent = $export->toHtml();

        // Enregistrer le fichier HTML
        $filename = 'balance_comptable_' . date('Y-m-d') . '.html';
        Storage::put('public/' . $filename, $htmlContent);
        $path = storage_path('app/public/' . $filename);

        // Retourner le fichier HTML qui permettra de télécharger l'Excel
        return response()->file($path, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Exporter la balance comptable au format CSV.
     *
     * @OA\Get(
     *     path="/export-balance/csv",
     *     summary="Exporter la balance comptable au format CSV",
     *     tags={"Balance"},
     *     @OA\Response(
     *         response=200,
     *         description="Fichier CSV de la balance comptable",
     *         @OA\MediaType(
     *             mediaType="text/csv"
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function exportCsv()
    {
        $export = new BalanceExport();
        $csvContent = $export->toCsv();

        $filename = 'balance_comptable_' . date('Y-m-d') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
