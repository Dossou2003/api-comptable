import api from './api';
import mockData from '../utils/mockData';

// Déterminer si nous sommes en mode développement
const isDevelopment = process.env.NODE_ENV === 'development';

const getAllComptes = async () => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: utilisation des données fictives pour les comptes');
      return {
        data: {
          success: true,
          data: mockData.comptes
        }
      };
    }
    return await api.get('/comptes');
  } catch (error) {
    console.error('Erreur lors de la récupération des comptes:', error);
    // Retourner les données fictives en cas d'erreur
    return {
      data: {
        success: true,
        data: mockData.comptes
      }
    };
  }
};

const getCompteById = async (id) => {
  try {
    if (isDevelopment) {
      console.log(`Mode développement: utilisation des données fictives pour le compte ${id}`);
      const compte = mockData.comptes.find(c => c.id === parseInt(id));
      if (!compte) {
        throw new Error('Compte non trouvé');
      }
      return {
        data: {
          success: true,
          data: compte
        }
      };
    }
    return await api.get(`/comptes/${id}`);
  } catch (error) {
    console.error(`Erreur lors de la récupération du compte ${id}:`, error);
    // Retourner une erreur si le compte n'est pas trouvé
    throw error;
  }
};

const createCompte = async (data) => {
  try {
    if (isDevelopment) {
      console.log('Mode développement: simulation de la création d\'un compte');
      const newCompte = {
        id: mockData.comptes.length + 1,
        ...data,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      };
      mockData.comptes.push(newCompte);
      return {
        data: {
          success: true,
          data: newCompte,
          message: 'Compte créé avec succès'
        }
      };
    }
    return await api.post('/comptes', data);
  } catch (error) {
    console.error('Erreur lors de la création du compte:', error);
    throw error;
  }
};

const updateCompte = async (id, data) => {
  try {
    if (isDevelopment) {
      console.log(`Mode développement: simulation de la mise à jour du compte ${id}`);
      const index = mockData.comptes.findIndex(c => c.id === parseInt(id));
      if (index === -1) {
        throw new Error('Compte non trouvé');
      }
      const updatedCompte = {
        ...mockData.comptes[index],
        ...data,
        updated_at: new Date().toISOString()
      };
      mockData.comptes[index] = updatedCompte;
      return {
        data: {
          success: true,
          data: updatedCompte,
          message: 'Compte mis à jour avec succès'
        }
      };
    }
    return await api.put(`/comptes/${id}`, data);
  } catch (error) {
    console.error(`Erreur lors de la mise à jour du compte ${id}:`, error);
    throw error;
  }
};

const deleteCompte = async (id) => {
  try {
    if (isDevelopment) {
      console.log(`Mode développement: simulation de la suppression du compte ${id}`);
      const index = mockData.comptes.findIndex(c => c.id === parseInt(id));
      if (index === -1) {
        throw new Error('Compte non trouvé');
      }
      mockData.comptes.splice(index, 1);
      return {
        data: {
          success: true,
          message: 'Compte supprimé avec succès'
        }
      };
    }
    return await api.delete(`/comptes/${id}`);
  } catch (error) {
    console.error(`Erreur lors de la suppression du compte ${id}:`, error);
    throw error;
  }
};

export default {
  getAllComptes,
  getCompteById,
  createCompte,
  updateCompte,
  deleteCompte
};
