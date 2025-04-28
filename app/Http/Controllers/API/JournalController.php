<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EntreeJournal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    /**
     * Afficher l'historique des écritures.
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
