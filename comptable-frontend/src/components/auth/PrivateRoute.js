import React from 'react';
import { Navigate } from 'react-router-dom';
import authService from '../../services/auth.service';

const PrivateRoute = ({ children }) => {
  const isAuthenticated = authService.isAuthenticated();
  
  if (!isAuthenticated) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
    return <Navigate to="/login" replace />;
  }
  
  return children;
};

export default PrivateRoute;
