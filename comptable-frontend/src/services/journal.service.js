import api from './api';
import mockData from '../utils/mockData';

// Déterminer si nous sommes en mode développement
const isDevelopment = process.env.NODE_ENV === 'development';

const getJournalEntries = async () => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: utilisation des données fictives pour le journal');
      return {
        data: {
          success: true,
          data: mockData.journalEntries
        }
      };
    }
    return await api.get('/journal');
  } catch (error) {
    console.error('Erreur lors de la récupération du journal:', error);
    // Retourner les données fictives en cas d'erreur
    return {
      data: {
        success: true,
        data: mockData.journalEntries
      }
    };
  }
};

export default {
  getJournalEntries
};
