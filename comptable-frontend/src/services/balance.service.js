import api from './api';
import mockData from '../utils/mockData';

// Déterminer si nous sommes en mode développement
const isDevelopment = process.env.NODE_ENV === 'development';

const exportExcel = async () => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: simulation de l\'export Excel');

      // Créer un fichier Excel fictif
      const blob = new Blob(['Données fictives pour l\'export Excel'], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
      return { data: blob };
    }
    return await api.get('/export-balance', { responseType: 'blob' });
  } catch (error) {
    console.error('Erreur lors de l\'export Excel:', error);
    throw error;
  }
};

const exportCsv = async () => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: simulation de l\'export CSV');

      // Créer un fichier CSV fictif
      let csvContent = 'Code,Nom,Type,Débit,Crédit,Solde\n';

      mockData.comptes.forEach(compte => {
        let debit = 0;
        let credit = 0;

        if (compte.type === 'actif' || compte.type === 'charge') {
          debit = parseFloat(compte.solde);
        } else {
          credit = parseFloat(compte.solde);
        }

        csvContent += `${compte.code},${compte.nom},${compte.type},${debit.toFixed(2)},${credit.toFixed(2)},${parseFloat(compte.solde).toFixed(2)}\n`;
      });

      const blob = new Blob([csvContent], { type: 'text/csv' });
      return { data: blob };
    }
    return await api.get('/export-balance/csv', { responseType: 'blob' });
  } catch (error) {
    console.error('Erreur lors de l\'export CSV:', error);
    throw error;
  }
};

// Fonction pour obtenir les données de la balance
const getBalanceData = async () => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: utilisation des données fictives pour la balance');

      // Calculer les totaux
      const totals = {
        actif: 0,
        passif: 0,
        produit: 0,
        charge: 0,
        debit: 0,
        credit: 0,
        solde: 0
      };

      mockData.comptes.forEach(compte => {
        totals[compte.type] += parseFloat(compte.solde);

        if (compte.type === 'actif' || compte.type === 'charge') {
          totals.debit += parseFloat(compte.solde);
        } else {
          totals.credit += parseFloat(compte.solde);
        }
      });

      return {
        data: {
          success: true,
          data: {
            comptes: mockData.comptes,
            totals: totals
          }
        }
      };
    }
    return await api.get('/balance');
  } catch (error) {
    console.error('Erreur lors de la récupération de la balance:', error);
    throw error;
  }
};

export default {
  exportExcel,
  exportCsv,
  getBalanceData
};
