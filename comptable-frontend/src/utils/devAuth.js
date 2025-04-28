/**
 * Utilitaire pour simuler l'authentification en mode développement
 * Cela permet de tester l'application sans avoir besoin d'une API fonctionnelle
 */

// Utilisateurs de test
const users = [
  {
    id: 1,
    nom: 'Admin',
    prenom: 'Système',
    email: 'admin@example.com',
    password: 'password',
    role: 'admin'
  },
  {
    id: 2,
    nom: 'Comptable',
    prenom: 'Test',
    email: 'comptable@example.com',
    password: 'password',
    role: 'comptable'
  },
  {
    id: 3,
    nom: 'Utilisateur',
    prenom: 'Standard',
    email: 'user@example.com',
    password: 'password',
    role: 'utilisateur'
  }
];

// Fonction pour simuler la connexion
export const fakeLogin = (email, password) => {
  const user = users.find(u => u.email === email && u.password === password);
  
  if (user) {
    const { password, ...userWithoutPassword } = user;
    return {
      success: true,
      token: `fake-jwt-token-${user.id}`,
      user: userWithoutPassword
    };
  }
  
  throw new Error('Email ou mot de passe incorrect');
};

// Fonction pour vérifier si l'utilisateur est connecté
export const isUserLoggedIn = () => {
  return !!localStorage.getItem('token');
};

// Fonction pour obtenir l'utilisateur connecté
export const getLoggedInUser = () => {
  const user = localStorage.getItem('user');
  return user ? JSON.parse(user) : null;
};

export default {
  fakeLogin,
  isUserLoggedIn,
  getLoggedInUser,
  users
};
