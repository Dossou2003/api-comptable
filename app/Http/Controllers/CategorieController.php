<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Afficher une liste des catégories.
     */
    public function index()
    {
        $categories = Categorie::withCount('produits')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle catégorie.
     */
    public function creer()
    {
        return view('categories.creer');
    }

    /**
     * Enregistrer une nouvelle catégorie.
     */
    public function enregistrer(Request $request)
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
        ]);

        Categorie::create($donnees);

        return redirect()->route('categories.index')
            ->with('succes', 'Catégorie créée avec succès.');
    }

    /**
     * Afficher les détails d'une catégorie spécifique.
     */
    public function afficher(Categorie $categorie)
    {
        $categorie->load('produits');
        return view('categories.afficher', compact('categorie'));
    }

    /**
     * Afficher le formulaire de modification d'une catégorie.
     */
    public function modifier(Categorie $categorie)
    {
        return view('categories.modifier', compact('categorie'));
    }

    /**
     * Mettre à jour une catégorie spécifique.
     */
    public function mettreAJour(Request $request, Categorie $categorie)
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom,' . $categorie->id],
            'description' => ['nullable', 'string'],
        ]);

        $categorie->update($donnees);

        return redirect()->route('categories.index')
            ->with('succes', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie spécifique.
     */
    public function supprimer(Categorie $categorie)
    {
        // Vérifier si la catégorie a des produits
        if ($categorie->produits()->count() > 0) {
            return back()->withErrors(['erreur' => 'Impossible de supprimer cette catégorie car elle contient des produits.']);
        }

        $categorie->delete();

        return redirect()->route('categories.index')
            ->with('succes', 'Catégorie supprimée avec succès.');
    }
}
