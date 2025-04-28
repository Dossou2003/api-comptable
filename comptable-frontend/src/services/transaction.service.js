import api from './api';
import mockData from '../utils/mockData';

// Déterminer si nous sommes en mode développement
const isDevelopment = process.env.NODE_ENV === 'development';

const getAllTransactions = async () => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: utilisation des données fictives pour les transactions');
      return {
        data: {
          success: true,
          data: mockData.transactions
        }
      };
    }
    return await api.get('/transactions');
  } catch (error) {
    console.error('Erreur lors de la récupération des transactions:', error);
    // Retourner les données fictives en cas d'erreur
    return {
      data: {
        success: true,
        data: mockData.transactions
      }
    };
  }
};

const getTransactionById = async (id) => {
  try {
    if (isDevelopment) {
      console.log(`Mode développement: utilisation des données fictives pour la transaction ${id}`);
      const transaction = mockData.transactions.find(t => t.id === parseInt(id));
      if (!transaction) {
        throw new Error('Transaction non trouvée');
      }
      return {
        data: {
          success: true,
          data: transaction
        }
      };
    }
    return await api.get(`/transactions/${id}`);
  } catch (error) {
    console.error(`Erreur lors de la récupération de la transaction ${id}:`, error);
    // Retourner une erreur si la transaction n'est pas trouvée
    throw error;
  }
};

const createTransaction = async (data) => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: simulation de la création d\'une transaction');

      // Trouver les comptes correspondants
      const compteDebit = mockData.comptes.find(c => c.id === parseInt(data.compte_debit_id));
      const compteCredit = mockData.comptes.find(c => c.id === parseInt(data.compte_credit_id));

      if (!compteDebit || !compteCredit) {
        throw new Error('Compte non trouvé');
      }

      const newTransaction = {
        id: mockData.transactions.length + 1,
        ...data,
        compte_debit: compteDebit,
        compte_credit: compteCredit,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      };

      // Mettre à jour les soldes des comptes
      const montant = parseFloat(data.montant);

      // Pour les comptes d'actif et de charge, un débit augmente le solde
      if (compteDebit.type === 'actif' || compteDebit.type === 'charge') {
        compteDebit.solde = parseFloat(compteDebit.solde) + montant;
      } else {
        // Pour les comptes de passif et de produit, un débit diminue le solde
        compteDebit.solde = parseFloat(compteDebit.solde) - montant;
      }

      // Pour les comptes de passif et de produit, un crédit augmente le solde
      if (compteCredit.type === 'passif' || compteCredit.type === 'produit') {
        compteCredit.solde = parseFloat(compteCredit.solde) + montant;
      } else {
        // Pour les comptes d'actif et de charge, un crédit diminue le solde
        compteCredit.solde = parseFloat(compteCredit.solde) - montant;
      }

      mockData.transactions.push(newTransaction);

      // Créer une entrée dans le journal
      const newJournalEntry = {
        id: mockData.journalEntries.length + 1,
        transaction_id: newTransaction.id,
        transaction: newTransaction,
        utilisateur: {
          id: 1,
          nom: 'Admin',
          prenom: 'Système',
          email: 'admin@example.com',
          role: 'admin'
        },
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      };

      mockData.journalEntries.push(newJournalEntry);

      return {
        data: {
          success: true,
          data: newTransaction,
          message: 'Transaction créée avec succès'
        }
      };
    }
    return await api.post('/transactions', data);
  } catch (error) {
    console.error('Erreur lors de la création de la transaction:', error);
    throw error;
  }
};

const deleteTransaction = async (id) => {
  try {
    if (isDevelopment) {
      console.log(`Mode développement: simulation de la suppression de la transaction ${id}`);
      const index = mockData.transactions.findIndex(t => t.id === parseInt(id));
      if (index === -1) {
        throw new Error('Transaction non trouvée');
      }

      const transaction = mockData.transactions[index];
      const compteDebit = mockData.comptes.find(c => c.id === transaction.compte_debit_id);
      const compteCredit = mockData.comptes.find(c => c.id === transaction.compte_credit_id);

      // Annuler les effets de la transaction sur les soldes des comptes
      const montant = parseFloat(transaction.montant);

      // Inverser les effets sur le compte débité
      if (compteDebit.type === 'actif' || compteDebit.type === 'charge') {
        compteDebit.solde = parseFloat(compteDebit.solde) - montant;
      } else {
        compteDebit.solde = parseFloat(compteDebit.solde) + montant;
      }

      // Inverser les effets sur le compte crédité
      if (compteCredit.type === 'passif' || compteCredit.type === 'produit') {
        compteCredit.solde = parseFloat(compteCredit.solde) - montant;
      } else {
        compteCredit.solde = parseFloat(compteCredit.solde) + montant;
      }

      // Supprimer la transaction
      mockData.transactions.splice(index, 1);

      // Supprimer l'entrée correspondante dans le journal
      const journalIndex = mockData.journalEntries.findIndex(j => j.transaction_id === parseInt(id));
      if (journalIndex !== -1) {
        mockData.journalEntries.splice(journalIndex, 1);
      }

      return {
        data: {
          success: true,
          message: 'Transaction supprimée avec succès'
        }
      };
    }
    return await api.delete(`/transactions/${id}`);
  } catch (error) {
    console.error(`Erreur lors de la suppression de la transaction ${id}:`, error);
    throw error;
  }
};

export default {
  getAllTransactions,
  getTransactionById,
  createTransaction,
  deleteTransaction
};
