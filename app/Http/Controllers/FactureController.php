<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facture;
use App\Models\LigneFacture;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    /**
     * Afficher une liste des factures.
     */
    public function index()
    {
        $factures = Facture::with(['client', 'createur'])->get();
        return view('factures.index', compact('factures'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle facture.
     */
    public function creer()
    {
        $clients = Client::all();
        $produits = Produit::all();
        return view('factures.creer', compact('clients', 'produits'));
    }

    // Cette fonction permet de sauvegarder une nouvelle facture
    // C'est un peu compliqué car il faut calculer les montants et créer les lignes de facture
    public function enregistrer(Request $request)
    {
        // D'abord on vérifie que toutes les données sont correctes
        // Si quelque chose ne va pas, Laravel nous renvoie au formulaire avec les erreurs
        $donnees = $request->validate([
            'numero' => 'required|string|max:50|unique:factures', // Le numéro doit être unique
            'date_emission' => 'required|date', // La date d'émission est obligatoire
            'date_echeance' => 'required|date|after_or_equal:date_emission', // La date d'échéance doit être après la date d'émission
            'statut' => 'required|in:brouillon,envoyée,payée,annulée', // Le statut doit être une de ces valeurs
            'notes' => 'nullable|string', // Les notes sont optionnelles
            'client_id' => 'required|exists:clients,id', // Le client doit exister dans la base de données
            'produits' => 'required|array|min:1', // Il faut au moins un produit
            'produits.*.produit_id' => 'required|exists:produits,id', // Chaque produit doit exister
            'produits.*.quantite' => 'required|numeric|min:0.01', // La quantité doit être positive
            'produits.*.prix_unitaire' => 'required|numeric|min:0', // Le prix unitaire doit être positif
            'produits.*.description' => 'required|string', // La description est obligatoire
        ]);

        // On utilise try/catch pour gérer les erreurs
        // Si quelque chose ne va pas, on annule tout
        try {
            // On commence une transaction pour être sûr que tout se passe bien
            // Si une erreur se produit, on peut tout annuler
            DB::beginTransaction();

            // On initialise les variables pour calculer les montants totaux
            $montantHT = 0; // Montant hors taxes
            $montantTVA = 0; // Montant de la TVA
            $montantTTC = 0; // Montant toutes taxes comprises
            $tauxTVA = 0; // On garde le dernier taux de TVA (pas idéal mais simplifié)

            // On parcourt tous les produits pour calculer les montants
            foreach ($donnees['produits'] as $produit) {
                // Calcul du montant HT pour cette ligne
                $montantLigneHT = $produit['quantite'] * $produit['prix_unitaire'];

                // On récupère le produit pour avoir son taux de TVA
                $produitObj = Produit::find($produit['produit_id']);
                $tauxTVA = $produitObj->taux_tva;

                // Calcul de la TVA et du montant TTC pour cette ligne
                $montantLigneTVA = $montantLigneHT * ($tauxTVA / 100);
                $montantLigneTTC = $montantLigneHT + $montantLigneTVA;

                // On ajoute aux totaux
                $montantHT += $montantLigneHT;
                $montantTVA += $montantLigneTVA;
                $montantTTC += $montantLigneTTC;
            }

            // Maintenant on crée la facture avec toutes les données
            $facture = Facture::create([
                'numero' => $donnees['numero'],
                'date_emission' => $donnees['date_emission'],
                'date_echeance' => $donnees['date_echeance'],
                'statut' => $donnees['statut'],
                'montant_ht' => $montantHT,
                'taux_tva' => $tauxTVA, // On utilise le dernier taux de TVA (pas idéal mais simplifié)
                'montant_tva' => $montantTVA,
                'montant_ttc' => $montantTTC,
                'notes' => $donnees['notes'],
                'client_id' => $donnees['client_id'],
                'createur_id' => Auth::id(), // L'utilisateur connecté est le créateur
            ]);

            // Maintenant on crée les lignes de facture pour chaque produit
            foreach ($donnees['produits'] as $produit) {
                // On refait les calculs pour chaque ligne
                $montantLigneHT = $produit['quantite'] * $produit['prix_unitaire'];
                $produitObj = Produit::find($produit['produit_id']);
                $tauxTVA = $produitObj->taux_tva;
                $montantLigneTVA = $montantLigneHT * ($tauxTVA / 100);
                $montantLigneTTC = $montantLigneHT + $montantLigneTVA;

                // On crée la ligne de facture
                LigneFacture::create([
                    'description' => $produit['description'],
                    'quantite' => $produit['quantite'],
                    'prix_unitaire' => $produit['prix_unitaire'],
                    'montant_ht' => $montantLigneHT,
                    'taux_tva' => $tauxTVA,
                    'montant_tva' => $montantLigneTVA,
                    'montant_ttc' => $montantLigneTTC,
                    'facture_id' => $facture->id, // On lie la ligne à la facture
                    'produit_id' => $produit['produit_id'],
                ]);
            }

            // Si tout s'est bien passé, on valide la transaction
            DB::commit();

            // On redirige vers la liste des factures avec un message de succès
            return redirect()->route('factures.index')
                ->with('succes', 'Youpi! La facture a été créée avec succès.');
        } catch (\Exception $e) {
            // Si une erreur s'est produite, on annule la transaction
            DB::rollBack();

            // On affiche un message d'erreur et on retourne au formulaire
            // avec les données déjà saisies
            return back()->withErrors(['erreur' => 'Oups! Une erreur est survenue lors de la création de la facture: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Afficher les détails d'une facture spécifique.
     */
    public function afficher(Facture $facture)
    {
        $facture->load(['client', 'createur', 'lignesFacture.produit']);
        return view('factures.afficher', compact('facture'));
    }

    /**
     * Afficher le formulaire de modification d'une facture.
     */
    public function modifier(Facture $facture)
    {
        $clients = Client::all();
        $produits = Produit::all();
        $facture->load('lignesFacture.produit');
        return view('factures.modifier', compact('facture', 'clients', 'produits'));
    }

    /**
     * Mettre à jour une facture spécifique.
     */
    public function mettreAJour(Request $request, Facture $facture)
    {
        $donnees = $request->validate([
            'numero' => ['required', 'string', 'max:50', 'unique:factures,numero,' . $facture->id],
            'date_emission' => ['required', 'date'],
            'date_echeance' => ['required', 'date', 'after_or_equal:date_emission'],
            'statut' => ['required', 'in:brouillon,envoyée,payée,annulée'],
            'notes' => ['nullable', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
            'produits' => ['required', 'array', 'min:1'],
            'produits.*.id' => ['nullable', 'exists:lignes_facture,id'],
            'produits.*.produit_id' => ['required', 'exists:produits,id'],
            'produits.*.quantite' => ['required', 'numeric', 'min:0.01'],
            'produits.*.prix_unitaire' => ['required', 'numeric', 'min:0'],
            'produits.*.description' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Calculer les montants
            $montantHT = 0;
            $montantTVA = 0;
            $montantTTC = 0;
            $tauxTVA = 0;

            foreach ($donnees['produits'] as $produit) {
                $montantLigneHT = $produit['quantite'] * $produit['prix_unitaire'];
                $produitObj = Produit::find($produit['produit_id']);
                $tauxTVA = $produitObj->taux_tva;
                $montantLigneTVA = $montantLigneHT * ($tauxTVA / 100);
                $montantLigneTTC = $montantLigneHT + $montantLigneTVA;

                $montantHT += $montantLigneHT;
                $montantTVA += $montantLigneTVA;
                $montantTTC += $montantLigneTTC;
            }

            // Mettre à jour la facture
            $facture->update([
                'numero' => $donnees['numero'],
                'date_emission' => $donnees['date_emission'],
                'date_echeance' => $donnees['date_echeance'],
                'statut' => $donnees['statut'],
                'montant_ht' => $montantHT,
                'taux_tva' => $tauxTVA,
                'montant_tva' => $montantTVA,
                'montant_ttc' => $montantTTC,
                'notes' => $donnees['notes'],
                'client_id' => $donnees['client_id'],
            ]);

            // Supprimer les anciennes lignes de facture
            $facture->lignesFacture()->delete();

            // Créer les nouvelles lignes de facture
            foreach ($donnees['produits'] as $produit) {
                $montantLigneHT = $produit['quantite'] * $produit['prix_unitaire'];
                $produitObj = Produit::find($produit['produit_id']);
                $tauxTVA = $produitObj->taux_tva;
                $montantLigneTVA = $montantLigneHT * ($tauxTVA / 100);
                $montantLigneTTC = $montantLigneHT + $montantLigneTVA;

                LigneFacture::create([
                    'description' => $produit['description'],
                    'quantite' => $produit['quantite'],
                    'prix_unitaire' => $produit['prix_unitaire'],
                    'montant_ht' => $montantLigneHT,
                    'taux_tva' => $tauxTVA,
                    'montant_tva' => $montantLigneTVA,
                    'montant_ttc' => $montantLigneTTC,
                    'facture_id' => $facture->id,
                    'produit_id' => $produit['produit_id'],
                ]);
            }

            DB::commit();

            return redirect()->route('factures.index')
                ->with('succes', 'Facture mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erreur' => 'Une erreur est survenue lors de la mise à jour de la facture.'])->withInput();
        }
    }

    // Cette fonction permet de supprimer une facture et toutes ses lignes
    // C'est dangereux car on perd toutes les données!
    public function supprimer(Facture $facture)
    {
        // On utilise try/catch pour attraper les erreurs
        try {
            // On commence une transaction pour être sûr que tout se passe bien
            // Une transaction permet d'annuler toutes les modifications si une erreur se produit
            DB::beginTransaction();

            // D'abord on supprime toutes les lignes de facture
            // Si on ne le fait pas, on aura une erreur de contrainte de clé étrangère
            // C'est comme si on essayait de supprimer un parent sans supprimer ses enfants
            $facture->lignesFacture()->delete();

            // Ensuite on peut supprimer la facture elle-même
            $facture->delete();

            // Si tout s'est bien passé, on valide la transaction
            DB::commit();

            // On redirige vers la liste des factures avec un message de succès
            return redirect()->route('factures.index')
                ->with('succes', 'Hourra! La facture a été supprimée avec succès.');
        } catch (\Exception $e) {
            // Si une erreur s'est produite, on annule la transaction
            DB::rollBack();

            // On affiche un message d'erreur
            // Le message d'erreur inclut le message de l'exception pour aider au débogage
            return back()->withErrors(['erreur' => 'Aïe! Impossible de supprimer cette facture: ' . $e->getMessage()]);
        }
    }

    // Cette fonction permet de générer un PDF de la facture
    // C'est utile pour envoyer la facture au client ou l'imprimer
    public function genererPDF(Facture $facture)
    {
        // On charge toutes les relations dont on a besoin pour le PDF
        // Le client pour ses coordonnées
        // Le créateur pour savoir qui a créé la facture
        // Les lignes de facture avec leurs produits pour le détail
        $facture->load(['client', 'createur', 'lignesFacture.produit']);

        // Ici normalement on utiliserait une bibliothèque pour générer le PDF
        // Par exemple DomPDF, TCPDF, ou Snappy PDF
        // Mais pour simplifier, on va juste afficher une vue HTML

        // TODO: Installer une bibliothèque PDF et générer un vrai PDF
        // Exemple avec DomPDF:
        // $pdf = PDF::loadView('factures.pdf', compact('facture'));
        // return $pdf->download('facture-' . $facture->numero . '.pdf');

        // Pour l'instant, on affiche juste la vue
        return view('factures.pdf', compact('facture'));
    }
}
