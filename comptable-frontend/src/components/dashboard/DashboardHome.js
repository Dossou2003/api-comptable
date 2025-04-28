import React from 'react';
import { 
  Typography, Grid, Paper, Box, Button, Card, CardContent, 
  CardActions, Divider, Container 
} from '@mui/material';
import { 
  AccountBalance, Receipt, Book, BarChart, Add, 
  ArrowForward, TrendingUp
} from '@mui/icons-material';
import { Link } from 'react-router-dom';
import StatCard from './StatCard';
import RecentTransactions from './RecentTransactions';
import authService from '../../services/auth.service';

const DashboardHome = () => {
  const isComptable = authService.isComptable() || authService.isAdmin();
  
  // Données fictives pour les tests
  const stats = {
    comptes: 12,
    transactions: 48,
    actif: 25000,
    passif: 15000
  };
  
  const transactions = [
    {
      id: 1,
      date: '2023-04-15',
      description: 'Achat de fournitures',
      montant: 250.50,
      compte_debit: { code: '6064', nom: 'Fournitures administratives' },
      compte_credit: { code: '5121', nom: 'Banque' }
    },
    {
      id: 2,
      date: '2023-04-14',
      description: 'Paiement facture client',
      montant: 1200.00,
      compte_debit: { code: '5121', nom: 'Banque' },
      compte_credit: { code: '7071', nom: 'Ventes de produits' }
    },
    {
      id: 3,
      date: '2023-04-12',
      description: 'Règlement loyer',
      montant: 800.00,
      compte_debit: { code: '6132', nom: 'Loyers' },
      compte_credit: { code: '5121', nom: 'Banque' }
    }
  ];

  return (
    <Container maxWidth="lg">
      <Box sx={{ mb: 4 }}>
        <Typography variant="h4" component="h1" gutterBottom>
          Tableau de bord
        </Typography>
        <Typography variant="body1" color="text.secondary">
          Bienvenue dans votre application de gestion comptable
        </Typography>
      </Box>

      <Grid container spacing={3} sx={{ mb: 4 }}>
        <Grid item xs={12} sm={6} md={3}>
          <StatCard 
            title="Comptes" 
            value={stats.comptes} 
            icon={AccountBalance} 
            color="primary"
            link="/comptes"
          />
        </Grid>
        
        <Grid item xs={12} sm={6} md={3}>
          <StatCard 
            title="Transactions" 
            value={stats.transactions} 
            icon={Receipt} 
            color="secondary"
            link="/transactions"
          />
        </Grid>
        
        <Grid item xs={12} sm={6} md={3}>
          <StatCard 
            title="Total Actif" 
            value={`${stats.actif.toLocaleString()} €`} 
            icon={TrendingUp} 
            color="success"
            link="/balance"
          />
        </Grid>
        
        <Grid item xs={12} sm={6} md={3}>
          <StatCard 
            title="Total Passif" 
            value={`${stats.passif.toLocaleString()} €`} 
            icon={BarChart} 
            color="info"
            link="/balance"
          />
        </Grid>
      </Grid>

      <Grid container spacing={3}>
        <Grid item xs={12} md={8}>
          <RecentTransactions transactions={transactions} />
        </Grid>
        
        <Grid item xs={12} md={4}>
          <Paper sx={{ p: 3, mb: 3 }}>
            <Typography variant="h6" gutterBottom>
              Actions rapides
            </Typography>
            <Divider sx={{ mb: 2 }} />
            
            <Box sx={{ display: 'flex', flexDirection: 'column', gap: 2 }}>
              {isComptable && (
                <Button 
                  variant="contained" 
                  startIcon={<Add />}
                  component={Link}
                  to="/transactions/new"
                  fullWidth
                >
                  Nouvelle transaction
                </Button>
              )}
              
              <Button 
                variant="outlined" 
                startIcon={<AccountBalance />}
                component={Link}
                to="/comptes"
                fullWidth
              >
                Gérer les comptes
              </Button>
              
              <Button 
                variant="outlined" 
                startIcon={<Book />}
                component={Link}
                to="/journal"
                fullWidth
              >
                Consulter le journal
              </Button>
              
              <Button 
                variant="outlined" 
                startIcon={<BarChart />}
                component={Link}
                to="/balance"
                fullWidth
              >
                Exporter la balance
              </Button>
            </Box>
          </Paper>
          
          <Paper sx={{ p: 3 }}>
            <Typography variant="h6" gutterBottom>
              Aide
            </Typography>
            <Divider sx={{ mb: 2 }} />
            
            <Typography variant="body2" paragraph>
              Cette application vous permet de gérer votre comptabilité de manière simple et efficace.
            </Typography>
            
            <Typography variant="body2" paragraph>
              Utilisez le menu de gauche pour naviguer entre les différentes sections.
            </Typography>
            
            <Typography variant="body2">
              Pour toute question, contactez l'administrateur système.
            </Typography>
          </Paper>
        </Grid>
      </Grid>
    </Container>
  );
};

export default DashboardHome;
