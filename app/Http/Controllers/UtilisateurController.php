<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    /**
     * Afficher une liste des utilisateurs.
     */
    public function index()
    {
        $utilisateurs = Utilisateur::all();
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Afficher le formulaire de création d'un nouvel utilisateur.
     */
    public function creer()
    {
        return view('utilisateurs.creer');
    }

    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function enregistrer(Request $request)
    {
        // Validation simplifiée, style développeur junior
        $request->validate([
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'email' => 'required|email|max:255',
            'mot_de_passe' => 'required|min:8',
            'mot_de_passe_confirmation' => 'required|same:mot_de_passe',
            'telephone' => 'nullable|max:20',
            'adresse' => 'nullable',
            'role' => 'required',
        ]);

        // Vérification manuelle de l'email unique
        $emailExiste = Utilisateur::where('email', $request->email)->exists();
        if ($emailExiste) {
            return back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
        }

        // Création de l'utilisateur avec assignation manuelle des champs
        $utilisateur = new Utilisateur();
        $utilisateur->nom = $request->nom;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->email = $request->email;
        $utilisateur->mot_de_passe = Hash::make($request->mot_de_passe);
        $utilisateur->telephone = $request->telephone;
        $utilisateur->adresse = $request->adresse;
        $utilisateur->role = $request->role;
        $utilisateur->save();

        return redirect()->route('utilisateurs.index')
            ->with('succes', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur spécifique.
     */
    public function afficher(Utilisateur $utilisateur)
    {
        return view('utilisateurs.afficher', compact('utilisateur'));
    }

    /**
     * Afficher le formulaire de modification d'un utilisateur.
     */
    public function modifier(Utilisateur $utilisateur)
    {
        return view('utilisateurs.modifier', compact('utilisateur'));
    }

    /**
     * Mettre à jour un utilisateur spécifique.
     */
    public function mettreAJour(Request $request, Utilisateur $utilisateur)
    {
        // Version simplifiée pour un développeur junior
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'role' => 'required|string',
        ]);

        // Vérification simple pour l'email unique
        $emailExiste = Utilisateur::where('email', $donnees['email'])
            ->where('id', '!=', $utilisateur->id)
            ->exists();

        if ($emailExiste) {
            return back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
        }

        // Gestion simplifiée du mot de passe
        if ($request->has('mot_de_passe') && !empty($request->mot_de_passe)) {
            if (strlen($request->mot_de_passe) < 8) {
                return back()->withErrors(['mot_de_passe' => 'Le mot de passe doit contenir au moins 8 caractères.'])->withInput();
            }

            if ($request->mot_de_passe != $request->mot_de_passe_confirmation) {
                return back()->withErrors(['mot_de_passe' => 'Les mots de passe ne correspondent pas.'])->withInput();
            }

            $donnees['mot_de_passe'] = Hash::make($request->mot_de_passe);
        }

        $utilisateur->update($donnees);

        return redirect()->route('utilisateurs.index')
            ->with('succes', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur spécifique.
     */
    public function supprimer(Utilisateur $utilisateur)
    {
        // Approche simplifiée pour un développeur junior
        try {
            
            $utilisateur->delete();

            return redirect()->route('utilisateurs.index')
                ->with('succes', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            // Gestion d'erreur simplifiée
            return redirect()->route('utilisateurs.index')
                ->with('erreur', 'Impossible de supprimer cet utilisateur.');
        }
    }
}
