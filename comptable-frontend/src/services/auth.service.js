import api from './api';
import devAuth from '../utils/devAuth';

// Déterminer si nous sommes en mode développement
const isDevelopment = process.env.NODE_ENV === 'development';

// Vérifier si nous devons simuler l'authentification en mode développement
// Nous utilisons une variable d'état pour permettre de se déconnecter correctement
const shouldSimulateAuth = isDevelopment && !localStorage.getItem('forceLogin');

const login = async (email, password) => {
  try {
    // En mode développement, utiliser l'authentification simulée
    if (shouldSimulateAuth) {
      console.log('Mode développement: utilisation de l\'authentification simulée');
      const result = devAuth.fakeLogin(email, password);
      localStorage.setItem('token', result.token);
      localStorage.setItem('user', JSON.stringify(result.user));
      // Supprimer le forceLogin pour que l'authentification simulée fonctionne à nouveau
      localStorage.removeItem('forceLogin');
      return result;
    }

    // En mode production ou si forceLogin est défini, utiliser l'API
    try {
      const response = await api.post('/login', { email, password });
      if (response.data.token) {
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      return response.data;
    } catch (apiError) {
      // Si nous sommes en mode développement et que l'API n'est pas disponible,
      // utiliser l'authentification simulée comme fallback
      if (isDevelopment) {
        console.log('API non disponible, utilisation de l\'authentification simulée comme fallback');
        const result = devAuth.fakeLogin(email, password);
        localStorage.setItem('token', result.token);
        localStorage.setItem('user', JSON.stringify(result.user));
        // Supprimer le forceLogin pour que l'authentification simulée fonctionne à nouveau
        localStorage.removeItem('forceLogin');
        return result;
      }
      throw apiError;
    }
  } catch (error) {
    console.error('Erreur de connexion:', error);
    throw error;
  }
};

const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  // Définir justLoggedOut pour afficher un message de succès sur la page de connexion
  localStorage.setItem('justLoggedOut', 'true');
  // En mode développement, définir forceLogin pour forcer l'affichage de la page de connexion
  if (isDevelopment) {
    localStorage.setItem('forceLogin', 'true');
  }
};

const getCurrentUser = () => {
  return JSON.parse(localStorage.getItem('user'));
};

const isAuthenticated = () => {
  // Si forceLogin est défini, l'utilisateur n'est pas authentifié
  if (localStorage.getItem('forceLogin')) {
    return false;
  }
  return !!localStorage.getItem('token');
};

const hasRole = (role) => {
  const user = getCurrentUser();
  return user && user.role === role;
};

const isAdmin = () => {
  return hasRole('admin');
};

const isComptable = () => {
  return hasRole('comptable');
};

export default {
  login,
  logout,
  getCurrentUser,
  isAuthenticated,
  hasRole,
  isAdmin,
  isComptable
};
