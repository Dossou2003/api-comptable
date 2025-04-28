import React from 'react';
import { useNavigate } from 'react-router-dom';
import {
  AppBar, Toolbar, Typography, Button, IconButton, Box,
  Menu, MenuItem, Avatar, Tooltip
} from '@mui/material';
import { AccountCircle } from '@mui/icons-material';
import authService from '../../services/auth.service';
import '../../styles/layout.css';

const Navbar = ({ children }) => {
  const navigate = useNavigate();
  const [anchorEl, setAnchorEl] = React.useState(null);
  const user = authService.getCurrentUser();

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const handleLogout = () => {
    console.log('Déconnexion...');
    authService.logout();
    // Forcer le rechargement de la page pour s'assurer que l'état est réinitialisé
    window.location.href = '/login';
  };

  const handleProfile = () => {
    handleClose();
    // Naviguer vers la page de profil (à implémenter)
    // navigate('/profile');
  };

  return (
    <AppBar position="fixed" className="navbar">
      <Toolbar>
        {children}

        <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
          API Comptable
        </Typography>

        {user && (
          <Box sx={{ display: 'flex', alignItems: 'center' }}>
            <Typography variant="body1" sx={{ mr: 2, display: { xs: 'none', sm: 'block' } }}>
              {user.role === 'admin' ? 'Administrateur' :
               user.role === 'comptable' ? 'Comptable' : 'Utilisateur'}
            </Typography>

            <Button
              variant="contained"
              color="error"
              onClick={handleLogout}
              className="logout-button"
              sx={{ mr: 2 }}
            >
              Déconnexion
            </Button>

            <Tooltip title="Paramètres du compte">
              <IconButton
                size="large"
                onClick={handleMenu}
                color="inherit"
              >
                <Avatar sx={{ width: 32, height: 32, bgcolor: 'secondary.main' }}>
                  <AccountCircle />
                </Avatar>
              </IconButton>
            </Tooltip>

            <Menu
              id="menu-appbar"
              anchorEl={anchorEl}
              anchorOrigin={{
                vertical: 'bottom',
                horizontal: 'right',
              }}
              keepMounted
              transformOrigin={{
                vertical: 'top',
                horizontal: 'right',
              }}
              open={Boolean(anchorEl)}
              onClose={handleClose}
            >
              <MenuItem onClick={handleProfile}>Mon profil</MenuItem>
              <MenuItem
                onClick={handleLogout}
                sx={{
                  color: 'error.main',
                  fontWeight: 'bold'
                }}
              >
                Déconnexion
              </MenuItem>
            </Menu>
          </Box>
        )}
      </Toolbar>
    </AppBar>
  );
};

export default Navbar;
