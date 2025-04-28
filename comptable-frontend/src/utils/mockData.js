/**
 * Données fictives pour le développement
 */

// Comptes fictifs
export const comptes = [
  {
    id: 1,
    code: '1000',
    nom: 'Capital',
    type: 'passif',
    solde: 50000
  },
  {
    id: 2,
    code: '2183',
    nom: 'Matériel informatique',
    type: 'actif',
    solde: 15000
  },
  {
    id: 3,
    code: '4111',
    nom: 'Clients',
    type: 'actif',
    solde: 25000
  },
  {
    id: 4,
    code: '4011',
    nom: 'Fournisseurs',
    type: 'passif',
    solde: 12000
  },
  {
    id: 5,
    code: '5121',
    nom: 'Banque',
    type: 'actif',
    solde: 35000
  },
  {
    id: 6,
    code: '6064',
    nom: 'Fournitures administratives',
    type: 'charge',
    solde: 1500
  },
  {
    id: 7,
    code: '6132',
    nom: 'Loyers',
    type: 'charge',
    solde: 8000
  },
  {
    id: 8,
    code: '7071',
    nom: 'Ventes de produits',
    type: 'produit',
    solde: 45000
  }
];

// Transactions fictives
export const transactions = [
  {
    id: 1,
    date: '2023-04-15',
    description: 'Achat de fournitures',
    montant: 250.50,
    compte_debit_id: 6,
    compte_credit_id: 5,
    compte_debit: comptes.find(c => c.id === 6),
    compte_credit: comptes.find(c => c.id === 5),
    created_at: '2023-04-15T10:30:00',
    updated_at: '2023-04-15T10:30:00'
  },
  {
    id: 2,
    date: '2023-04-14',
    description: 'Paiement facture client',
    montant: 1200.00,
    compte_debit_id: 5,
    compte_credit_id: 3,
    compte_debit: comptes.find(c => c.id === 5),
    compte_credit: comptes.find(c => c.id === 3),
    created_at: '2023-04-14T14:15:00',
    updated_at: '2023-04-14T14:15:00'
  },
  {
    id: 3,
    date: '2023-04-12',
    description: 'Règlement loyer',
    montant: 800.00,
    compte_debit_id: 7,
    compte_credit_id: 5,
    compte_debit: comptes.find(c => c.id === 7),
    compte_credit: comptes.find(c => c.id === 5),
    created_at: '2023-04-12T09:45:00',
    updated_at: '2023-04-12T09:45:00'
  },
  {
    id: 4,
    date: '2023-04-10',
    description: 'Vente de produits',
    montant: 3500.00,
    compte_debit_id: 3,
    compte_credit_id: 8,
    compte_debit: comptes.find(c => c.id === 3),
    compte_credit: comptes.find(c => c.id === 8),
    created_at: '2023-04-10T16:20:00',
    updated_at: '2023-04-10T16:20:00'
  },
  {
    id: 5,
    date: '2023-04-08',
    description: 'Achat de matériel informatique',
    montant: 2000.00,
    compte_debit_id: 2,
    compte_credit_id: 4,
    compte_debit: comptes.find(c => c.id === 2),
    compte_credit: comptes.find(c => c.id === 4),
    created_at: '2023-04-08T11:10:00',
    updated_at: '2023-04-08T11:10:00'
  }
];

// Entrées de journal fictives
export const journalEntries = transactions.map(transaction => ({
  id: transaction.id,
  transaction_id: transaction.id,
  transaction: transaction,
  utilisateur: {
    id: 1,
    nom: 'Admin',
    prenom: 'Système',
    email: 'admin@example.com',
    role: 'admin'
  },
  created_at: transaction.created_at,
  updated_at: transaction.updated_at
}));

export default {
  comptes,
  transactions,
  journalEntries
};
