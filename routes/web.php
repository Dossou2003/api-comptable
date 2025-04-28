<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\FactureController;

// Page d'accueil
Route::get('/', function () {
    return view('tableau-de-bord');
})->name('tableau-de-bord');

// Routes pour les utilisateurs
Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
Route::get('/utilisateurs/creer', [UtilisateurController::class, 'creer'])->name('utilisateurs.creer');
Route::post('/utilisateurs', [UtilisateurController::class, 'enregistrer'])->name('utilisateurs.enregistrer');
Route::get('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'afficher'])->name('utilisateurs.afficher');
Route::get('/utilisateurs/{utilisateur}/modifier', [UtilisateurController::class, 'modifier'])->name('utilisateurs.modifier');
Route::post('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'mettreAJour'])->name('utilisateurs.mettreAJour'); // Utilisation de POST au lieu de PUT pour simplifier
Route::get('/utilisateurs/{utilisateur}/supprimer', [UtilisateurController::class, 'supprimer'])->name('utilisateurs.supprimer'); // Utilisation de GET au lieu de DELETE pour simplifier

// Routes pour les clients
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/creer', [ClientController::class, 'creer'])->name('clients.creer');
Route::post('/clients', [ClientController::class, 'enregistrer'])->name('clients.enregistrer');
Route::get('/clients/{client}', [ClientController::class, 'afficher'])->name('clients.afficher');
Route::get('/clients/{client}/modifier', [ClientController::class, 'modifier'])->name('clients.modifier');
Route::post('/clients/{client}', [ClientController::class, 'mettreAJour'])->name('clients.mettreAJour'); // Utilisation de POST au lieu de PUT
Route::get('/clients/{client}/supprimer', [ClientController::class, 'supprimer'])->name('clients.supprimer'); // Utilisation de GET au lieu de DELETE

// Routes pour les catégories
Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
Route::get('/categories/creer', [CategorieController::class, 'creer'])->name('categories.creer');
Route::post('/categories', [CategorieController::class, 'enregistrer'])->name('categories.enregistrer');
Route::get('/categories/{categorie}', [CategorieController::class, 'afficher'])->name('categories.afficher');
Route::get('/categories/{categorie}/modifier', [CategorieController::class, 'modifier'])->name('categories.modifier');
Route::post('/categories/{categorie}', [CategorieController::class, 'mettreAJour'])->name('categories.mettreAJour'); // Utilisation de POST au lieu de PUT
Route::get('/categories/{categorie}/supprimer', [CategorieController::class, 'supprimer'])->name('categories.supprimer'); // Utilisation de GET au lieu de DELETE

// Routes pour les produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
Route::get('/produits/creer', [ProduitController::class, 'creer'])->name('produits.creer');
Route::post('/produits', [ProduitController::class, 'enregistrer'])->name('produits.enregistrer');
Route::get('/produits/{produit}', [ProduitController::class, 'afficher'])->name('produits.afficher');
Route::get('/produits/{produit}/modifier', [ProduitController::class, 'modifier'])->name('produits.modifier');
Route::post('/produits/{produit}', [ProduitController::class, 'mettreAJour'])->name('produits.mettreAJour'); // Utilisation de POST au lieu de PUT
Route::get('/produits/{produit}/supprimer', [ProduitController::class, 'supprimer'])->name('produits.supprimer'); // Utilisation de GET au lieu de DELETE

// Routes pour les factures
Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
Route::get('/factures/creer', [FactureController::class, 'creer'])->name('factures.creer');
Route::post('/factures', [FactureController::class, 'enregistrer'])->name('factures.enregistrer');
Route::get('/factures/{facture}', [FactureController::class, 'afficher'])->name('factures.afficher');
Route::get('/factures/{facture}/modifier', [FactureController::class, 'modifier'])->name('factures.modifier');
Route::post('/factures/{facture}', [FactureController::class, 'mettreAJour'])->name('factures.mettreAJour'); // Utilisation de POST au lieu de PUT
Route::get('/factures/{facture}/supprimer', [FactureController::class, 'supprimer'])->name('factures.supprimer'); // Utilisation de GET au lieu de DELETE
Route::get('/factures/{facture}/pdf', [FactureController::class, 'genererPDF'])->name('factures.pdf');
