<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // affiche la liste des clients
    public function index()
    {
        // récupère les clients avec leurs gestionnaires
        $clients = Client::with('gestionnaire')->get();

        return view('clients.index', compact('clients'));
    }

    // formulaire de création
    public function creer()
    {
        // gestionnaires et admins
        $gestionnaires = Utilisateur::where('role', 'gestionnaire')
            ->orWhere('role', 'administrateur')
            ->get();

        return view('clients.creer', compact('gestionnaires'));
    }

    // enregistre un nouveau client
    public function enregistrer(Request $request)
    {
        // validation des données
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'societe' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'numero_tva' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'gestionnaire_id' => 'required|exists:utilisateurs,id',
        ]);

        // création du client
        Client::create($donnees);

        return redirect()->route('clients.index')
            ->with('succes', 'Client créé avec succès.');
    }

    // affiche les détails d'un client
    public function afficher(Client $client)
    {
        $client->load('gestionnaire', 'factures');
        return view('clients.afficher', compact('client'));
    }

    // formulaire de modification
    public function modifier(Client $client)
    {
        $gestionnaires = Utilisateur::where('role', 'gestionnaire')
            ->orWhere('role', 'administrateur')
            ->get();
        return view('clients.modifier', compact('client', 'gestionnaires'));
    }

    // met à jour un client
    public function mettreAJour(Request $request, Client $client)
    {
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'societe' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'numero_tva' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'gestionnaire_id' => 'required|exists:utilisateurs,id',
        ]);

        $client->update($donnees);

        return redirect()->route('clients.index')
            ->with('succes', 'Client mis à jour avec succès.');
    }

    // supprime un client
    public function supprimer(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('clients.index')
                ->with('succes', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            // log l'erreur
            error_log($e->getMessage());
            return redirect()->route('clients.index')
                ->with('erreur', 'Impossible de supprimer ce client.');
        }
    }
}
