import React from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import {
  List, ListItem, ListItemIcon, ListItemText,
  Divider, Box, Typography, Paper, Button
} from '@mui/material';
import {
  Dashboard as DashboardIcon,
  AccountBalance as AccountBalanceIcon,
  Receipt as ReceiptIcon,
  Book as BookIcon,
  BarChart as BarChartIcon,
  ExitToApp as ExitToAppIcon
} from '@mui/icons-material';
import authService from '../../services/auth.service';
import '../../styles/layout.css';

const Sidebar = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const user = authService.getCurrentUser();

  const handleLogout = () => {
    console.log('Déconnexion depuis la barre latérale...');
    authService.logout();
    // Forcer le rechargement de la page pour s'assurer que l'état est réinitialisé
    window.location.href = '/login';
  };

  const menuItems = [
    { text: 'Tableau de bord', icon: <DashboardIcon />, path: '/dashboard' },
    { text: 'Comptes', icon: <AccountBalanceIcon />, path: '/comptes' },
    { text: 'Transactions', icon: <ReceiptIcon />, path: '/transactions' },
    { text: 'Journal', icon: <BookIcon />, path: '/journal' },
    { text: 'Balance', icon: <BarChartIcon />, path: '/balance' },
  ];

  return (
    <Paper elevation={0} sx={{ height: '100%', borderRadius: 0 }}>
      <Box sx={{ p: 2, pt: 8 }}>
        <Typography variant="h6" component="div">
          Menu
        </Typography>
      </Box>
      <Divider />
      <List>
        {menuItems.map((item) => (
          <ListItem
            button
            key={item.text}
            component={Link}
            to={item.path}
            selected={location.pathname === item.path ||
                     (item.path !== '/dashboard' && location.pathname.startsWith(item.path))}
            sx={{
              '&.Mui-selected': {
                backgroundColor: 'primary.light',
                '&:hover': {
                  backgroundColor: 'primary.light',
                },
              },
            }}
          >
            <ListItemIcon>{item.icon}</ListItemIcon>
            <ListItemText primary={item.text} />
          </ListItem>
        ))}
      </List>
      <Divider />
      <Box sx={{ p: 2, mt: 'auto', position: 'absolute', bottom: 0, width: '100%' }}>
        <Typography variant="body2" color="text.secondary">
          Connecté en tant que:
        </Typography>
        <Typography variant="body1">
          {user?.email || 'admin@example.com'}
        </Typography>
        <Typography variant="body2" color="text.secondary" sx={{ mb: 2 }}>
          Rôle: {user?.role === 'admin' ? 'Administrateur' :
                user?.role === 'comptable' ? 'Comptable' : 'Utilisateur'}
        </Typography>

        <Button
          variant="contained"
          color="error"
          fullWidth
          startIcon={<ExitToAppIcon />}
          onClick={handleLogout}
          sx={{ mt: 2 }}
        >
          Déconnexion
        </Button>
      </Box>
    </Paper>
  );
};

export default Sidebar;
