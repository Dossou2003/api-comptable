<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Afficher une liste des produits.
     */
    public function index()
    {
        $produits = Produit::with('categorie')->get();
        return view('produits.index', compact('produits'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau produit.
     */
    public function creer()
    {
        $categories = Categorie::all();
        return view('produits.creer', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit.
     */
    public function enregistrer(Request $request)
    {
        $donnees = $request->validate([
            'reference' => ['required', 'string', 'max:50', 'unique:produits'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'taux_tva' => ['required', 'numeric', 'min:0', 'max:100'],
            'categorie_id' => ['required', 'exists:categories,id'],
        ]);

        Produit::create($donnees);

        return redirect()->route('produits.index')
            ->with('succes', 'Produit créé avec succès.');
    }

    /**
     * Afficher les détails d'un produit spécifique.
     */
    public function afficher(Produit $produit)
    {
        $produit->load('categorie');
        return view('produits.afficher', compact('produit'));
    }

    /**
     * Afficher le formulaire de modification d'un produit.
     */
    public function modifier(Produit $produit)
    {
        $categories = Categorie::all();
        return view('produits.modifier', compact('produit', 'categories'));
    }

    /**
     * Mettre à jour un produit spécifique.
     */
    public function mettreAJour(Request $request, Produit $produit)
    {
        $donnees = $request->validate([
            'reference' => ['required', 'string', 'max:50', 'unique:produits,reference,' . $produit->id],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'taux_tva' => ['required', 'numeric', 'min:0', 'max:100'],
            'categorie_id' => ['required', 'exists:categories,id'],
        ]);

        $produit->update($donnees);

        return redirect()->route('produits.index')
            ->with('succes', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprimer un produit spécifique.
     */
    public function supprimer(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')
            ->with('succes', 'Produit supprimé avec succès.');
    }
}
