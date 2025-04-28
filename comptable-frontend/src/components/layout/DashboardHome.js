import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { 
  Typography, Grid, Paper, Box, Button, 
  Card, CardContent, CardActions, Divider 
} from '@mui/material';
import { 
  AccountBalance as AccountBalanceIcon,
  Receipt as ReceiptIcon,
  Book as BookIcon,
  BarChart as BarChartIcon,
  Add as AddIcon
} from '@mui/icons-material';
import compteService from '../../services/compte.service';
import transactionService from '../../services/transaction.service';
import authService from '../../services/auth.service';

const DashboardHome = () => {
  const [comptes, setComptes] = useState([]);
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const isComptable = authService.isComptable();
  const isAdmin = authService.isAdmin();

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const [comptesResponse, transactionsResponse] = await Promise.all([
          compteService.getAllComptes(),
          transactionService.getAllTransactions()
        ]);
        
        setComptes(comptesResponse.data.data);
        setTransactions(transactionsResponse.data.data);
        setError('');
      } catch (err) {
        setError('Erreur lors du chargement des données');
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  // Calculer les statistiques
  const totalComptes = comptes.length;
  const totalTransactions = transactions.length;
  const totalActif = comptes
    .filter(compte => compte.type === 'actif')
    .reduce((sum, compte) => sum + parseFloat(compte.solde), 0);
  const totalPassif = comptes
    .filter(compte => compte.type === 'passif')
    .reduce((sum, compte) => sum + parseFloat(compte.solde), 0);

  if (loading) return <Typography>Chargement...</Typography>;
  if (error) return <Typography color="error">{error}</Typography>;

  return (
    <Box>
      <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mb: 4 }}>
        <Typography variant="h4" component="h1" gutterBottom>
          Tableau de bord
        </Typography>
        
        {isComptable && (
          <Button 
            variant="contained" 
            color="primary" 
            startIcon={<AddIcon />}
            component={Link}
            to="/transactions/new"
          >
            Nouvelle transaction
          </Button>
        )}
      </Box>

      <Grid container spacing={3}>
        {/* Statistiques */}
        <Grid item xs={12} md={3}>
          <Paper
            sx={{
              p: 2,
              display: 'flex',
              flexDirection: 'column',
              height: 140,
              bgcolor: 'primary.light',
              color: 'white',
            }}
          >
            <Typography component="h2" variant="h6" color="inherit" gutterBottom>
              Comptes
            </Typography>
            <Typography component="p" variant="h4">
              {totalComptes}
            </Typography>
            <Typography variant="body2" sx={{ mt: 'auto' }}>
              <Link to="/comptes" style={{ color: 'inherit' }}>
                Voir tous les comptes
              </Link>
            </Typography>
          </Paper>
        </Grid>
        
        <Grid item xs={12} md={3}>
          <Paper
            sx={{
              p: 2,
              display: 'flex',
              flexDirection: 'column',
              height: 140,
              bgcolor: 'secondary.light',
              color: 'white',
            }}
          >
            <Typography component="h2" variant="h6" color="inherit" gutterBottom>
              Transactions
            </Typography>
            <Typography component="p" variant="h4">
              {totalTransactions}
            </Typography>
            <Typography variant="body2" sx={{ mt: 'auto' }}>
              <Link to="/transactions" style={{ color: 'inherit' }}>
                Voir toutes les transactions
              </Link>
            </Typography>
          </Paper>
        </Grid>
        
        <Grid item xs={12} md={3}>
          <Paper
            sx={{
              p: 2,
              display: 'flex',
              flexDirection: 'column',
              height: 140,
              bgcolor: 'success.light',
              color: 'white',
            }}
          >
            <Typography component="h2" variant="h6" color="inherit" gutterBottom>
              Total Actif
            </Typography>
            <Typography component="p" variant="h4">
              {totalActif.toFixed(2)} €
            </Typography>
            <Typography variant="body2" sx={{ mt: 'auto' }}>
              <Link to="/balance" style={{ color: 'inherit' }}>
                Voir la balance
              </Link>
            </Typography>
          </Paper>
        </Grid>
        
        <Grid item xs={12} md={3}>
          <Paper
            sx={{
              p: 2,
              display: 'flex',
              flexDirection: 'column',
              height: 140,
              bgcolor: 'info.light',
              color: 'white',
            }}
          >
            <Typography component="h2" variant="h6" color="inherit" gutterBottom>
              Total Passif
            </Typography>
            <Typography component="p" variant="h4">
              {totalPassif.toFixed(2)} €
            </Typography>
            <Typography variant="body2" sx={{ mt: 'auto' }}>
              <Link to="/balance" style={{ color: 'inherit' }}>
                Voir la balance
              </Link>
            </Typography>
          </Paper>
        </Grid>

        {/* Dernières transactions */}
        <Grid item xs={12}>
          <Paper sx={{ p: 2 }}>
            <Typography component="h2" variant="h6" color="primary" gutterBottom>
              Dernières transactions
            </Typography>
            <Box sx={{ mt: 2 }}>
              {transactions.slice(0, 5).map((transaction) => (
                <Box key={transaction.id} sx={{ mb: 2 }}>
                  <Grid container spacing={2}>
                    <Grid item xs={2}>
                      <Typography variant="body2" color="text.secondary">
                        {new Date(transaction.date).toLocaleDateString()}
                      </Typography>
                    </Grid>
                    <Grid item xs={4}>
                      <Typography variant="body1">
                        {transaction.description}
                      </Typography>
                    </Grid>
                    <Grid item xs={2}>
                      <Typography variant="body2">
                        {transaction.compte_debit?.code} - {transaction.compte_debit?.nom}
                      </Typography>
                    </Grid>
                    <Grid item xs={2}>
                      <Typography variant="body2">
                        {transaction.compte_credit?.code} - {transaction.compte_credit?.nom}
                      </Typography>
                    </Grid>
                    <Grid item xs={2}>
                      <Typography variant="body1" align="right" fontWeight="bold">
                        {parseFloat(transaction.montant).toFixed(2)} €
                      </Typography>
                    </Grid>
                  </Grid>
                  <Divider sx={{ mt: 1 }} />
                </Box>
              ))}
              {transactions.length > 5 && (
                <Box sx={{ mt: 2, textAlign: 'center' }}>
                  <Button 
                    component={Link} 
                    to="/transactions" 
                    variant="outlined"
                  >
                    Voir toutes les transactions
                  </Button>
                </Box>
              )}
            </Box>
          </Paper>
        </Grid>

        {/* Accès rapides */}
        <Grid item xs={12}>
          <Typography component="h2" variant="h6" color="primary" gutterBottom>
            Accès rapides
          </Typography>
          <Grid container spacing={3}>
            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                    <AccountBalanceIcon color="primary" sx={{ mr: 1 }} />
                    <Typography variant="h6" component="div">
                      Comptes
                    </Typography>
                  </Box>
                  <Typography variant="body2" color="text.secondary">
                    Gérez vos comptes comptables
                  </Typography>
                </CardContent>
                <CardActions>
                  <Button size="small" component={Link} to="/comptes">Voir les comptes</Button>
                  <Button size="small" component={Link} to="/comptes/new">Nouveau compte</Button>
                </CardActions>
              </Card>
            </Grid>
            
            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                    <ReceiptIcon color="secondary" sx={{ mr: 1 }} />
                    <Typography variant="h6" component="div">
                      Transactions
                    </Typography>
                  </Box>
                  <Typography variant="body2" color="text.secondary">
                    Gérez vos écritures comptables
                  </Typography>
                </CardContent>
                <CardActions>
                  <Button size="small" component={Link} to="/transactions">Voir les transactions</Button>
                  {isComptable && (
                    <Button size="small" component={Link} to="/transactions/new">Nouvelle transaction</Button>
                  )}
                </CardActions>
              </Card>
            </Grid>
            
            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                    <BookIcon color="success" sx={{ mr: 1 }} />
                    <Typography variant="h6" component="div">
                      Journal
                    </Typography>
                  </Box>
                  <Typography variant="body2" color="text.secondary">
                    Consultez le journal comptable
                  </Typography>
                </CardContent>
                <CardActions>
                  <Button size="small" component={Link} to="/journal">Voir le journal</Button>
                </CardActions>
              </Card>
            </Grid>
            
            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                    <BarChartIcon color="info" sx={{ mr: 1 }} />
                    <Typography variant="h6" component="div">
                      Balance
                    </Typography>
                  </Box>
                  <Typography variant="body2" color="text.secondary">
                    Exportez la balance comptable
                  </Typography>
                </CardContent>
                <CardActions>
                  <Button size="small" component={Link} to="/balance">Exporter la balance</Button>
                </CardActions>
              </Card>
            </Grid>
          </Grid>
        </Grid>
      </Grid>
    </Box>
  );
};

export default DashboardHome;
