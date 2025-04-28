import React, { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { 
  Typography, Box, Paper, Grid, Divider, Button, 
  Chip, Alert, Card, CardContent, List, ListItem,
  ListItemText, ListItemSecondaryAction, Tab, Tabs
} from '@mui/material';
import { 
  ArrowBack, Edit, Delete, AccountBalance, 
  Receipt, Timeline
} from '@mui/icons-material';
import compteService from '../../services/compte.service';
import transactionService from '../../services/transaction.service';
import Loading from '../common/Loading';
import PageHeader from '../common/PageHeader';
import authService from '../../services/auth.service';

const CompteDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isAdmin = authService.isAdmin();
  
  const [compte, setCompte] = useState(null);
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [tabValue, setTabValue] = useState(0);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);

  useEffect(() => {
    fetchCompte();
  }, [id]);

  const fetchCompte = async () => {
    try {
      setLoading(true);
      const response = await compteService.getCompteById(id);
      setCompte(response.data.data);
      
      // Simuler la récupération des transactions liées à ce compte
      // Dans une vraie application, vous auriez un endpoint API pour cela
      const transactionsResponse = await transactionService.getAllTransactions();
      const allTransactions = transactionsResponse.data.data;
      const filteredTransactions = allTransactions.filter(
        t => t.compte_debit_id === parseInt(id) || t.compte_credit_id === parseInt(id)
      );
      setTransactions(filteredTransactions);
    } catch (err) {
      setError('Erreur lors du chargement du compte');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (event, newValue) => {
    setTabValue(newValue);
  };

  const handleDelete = () => {
    setDeleteDialogOpen(true);
  };

  const getTypeChip = (type) => {
    let color;
    switch (type) {
      case 'actif':
        color = 'primary';
        break;
      case 'passif':
        color = 'secondary';
        break;
      case 'produit':
        color = 'success';
        break;
      case 'charge':
        color = 'error';
        break;
      default:
        color = 'default';
    }
    
    return (
      <Chip 
        label={type.charAt(0).toUpperCase() + type.slice(1)} 
        color={color} 
        variant="outlined"
      />
    );
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
  };

  if (loading) {
    return <Loading />;
  }

  if (error) {
    return (
      <Box>
        <Alert severity="error" sx={{ mb: 2 }}>
          {error}
        </Alert>
        <Button 
          startIcon={<ArrowBack />} 
          onClick={() => navigate('/comptes')}
        >
          Retour à la liste des comptes
        </Button>
      </Box>
    );
  }

  if (!compte) {
    return (
      <Box>
        <Alert severity="warning" sx={{ mb: 2 }}>
          Compte non trouvé
        </Alert>
        <Button 
          startIcon={<ArrowBack />} 
          onClick={() => navigate('/comptes')}
        >
          Retour à la liste des comptes
        </Button>
      </Box>
    );
  }

  const breadcrumbs = [
    { label: 'Comptes', link: '/comptes' },
    { label: compte.nom, link: `/comptes/${compte.id}` }
  ];

  return (
    <Box>
      <PageHeader 
        title={`${compte.code} - ${compte.nom}`}
        breadcrumbs={breadcrumbs}
        showAction={false}
      />
      
      <Grid container spacing={3}>
        <Grid item xs={12} md={4}>
          <Paper sx={{ p: 3, mb: 3 }}>
            <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
              <AccountBalance sx={{ mr: 1, color: 'primary.main' }} />
              <Typography variant="h6">
                Détails du compte
              </Typography>
            </Box>
            
            <Divider sx={{ mb: 2 }} />
            
            <Box sx={{ mb: 2 }}>
              <Typography variant="body2" color="text.secondary">
                Code
              </Typography>
              <Typography variant="h6">
                {compte.code}
              </Typography>
            </Box>
            
            <Box sx={{ mb: 2 }}>
              <Typography variant="body2" color="text.secondary">
                Nom
              </Typography>
              <Typography variant="h6">
                {compte.nom}
              </Typography>
            </Box>
            
            <Box sx={{ mb: 2 }}>
              <Typography variant="body2" color="text.secondary">
                Type
              </Typography>
              <Box sx={{ mt: 0.5 }}>
                {getTypeChip(compte.type)}
              </Box>
            </Box>
            
            <Box sx={{ mb: 2 }}>
              <Typography variant="body2" color="text.secondary">
                Solde
              </Typography>
              <Typography variant="h4" color="primary" fontWeight="bold">
                {parseFloat(compte.solde).toFixed(2)} €
              </Typography>
            </Box>
            
            <Box sx={{ mt: 3, display: 'flex', justifyContent: 'space-between' }}>
              <Button 
                variant="outlined" 
                startIcon={<Edit />}
                component={Link}
                to={`/comptes/${compte.id}/edit`}
              >
                Modifier
              </Button>
              
              {isAdmin && (
                <Button 
                  variant="outlined" 
                  color="error"
                  startIcon={<Delete />}
                  onClick={handleDelete}
                >
                  Supprimer
                </Button>
              )}
            </Box>
          </Paper>
          
          <Card>
            <CardContent>
              <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                <Timeline sx={{ mr: 1, color: 'primary.main' }} />
                <Typography variant="h6">
                  Statistiques
                </Typography>
              </Box>
              
              <Divider sx={{ mb: 2 }} />
              
              <Box sx={{ mb: 1 }}>
                <Typography variant="body2" color="text.secondary">
                  Nombre de transactions
                </Typography>
                <Typography variant="h6">
                  {transactions.length}
                </Typography>
              </Box>
              
              <Box sx={{ mb: 1 }}>
                <Typography variant="body2" color="text.secondary">
                  Total des débits
                </Typography>
                <Typography variant="h6" color="primary">
                  {transactions
                    .filter(t => t.compte_debit_id === parseInt(id))
                    .reduce((sum, t) => sum + parseFloat(t.montant), 0)
                    .toFixed(2)} €
                </Typography>
              </Box>
              
              <Box>
                <Typography variant="body2" color="text.secondary">
                  Total des crédits
                </Typography>
                <Typography variant="h6" color="secondary">
                  {transactions
                    .filter(t => t.compte_credit_id === parseInt(id))
                    .reduce((sum, t) => sum + parseFloat(t.montant), 0)
                    .toFixed(2)} €
                </Typography>
              </Box>
            </CardContent>
          </Card>
        </Grid>
        
        <Grid item xs={12} md={8}>
          <Paper sx={{ mb: 3 }}>
            <Tabs
              value={tabValue}
              onChange={handleTabChange}
              indicatorColor="primary"
              textColor="primary"
              variant="fullWidth"
            >
              <Tab label="Transactions" />
              <Tab label="Historique" />
            </Tabs>
            
            <Divider />
            
            <Box sx={{ p: 3 }}>
              {tabValue === 0 && (
                <>
                  {transactions.length === 0 ? (
                    <Typography variant="body2" color="text.secondary" align="center" sx={{ py: 3 }}>
                      Aucune transaction pour ce compte
                    </Typography>
                  ) : (
                    <List disablePadding>
                      {transactions.map((transaction) => (
                        <React.Fragment key={transaction.id}>
                          <ListItem 
                            component={Link} 
                            to={`/transactions/${transaction.id}`}
                            sx={{ 
                              py: 1.5, 
                              px: 0,
                              textDecoration: 'none',
                              color: 'inherit',
                              '&:hover': {
                                backgroundColor: 'rgba(0, 0, 0, 0.04)',
                              }
                            }}
                          >
                            <Box sx={{ mr: 2, color: transaction.compte_debit_id === parseInt(id) ? 'primary.main' : 'secondary.main' }}>
                              <Receipt />
                            </Box>
                            
                            <ListItemText
                              primary={transaction.description}
                              secondary={`${formatDate(transaction.date)} • ${
                                transaction.compte_debit_id === parseInt(id) 
                                  ? `Débit de ${compte.code} vers ${transaction.compte_credit?.code}`
                                  : `Crédit de ${transaction.compte_debit?.code} vers ${compte.code}`
                              }`}
                            />
                            
                            <ListItemSecondaryAction>
                              <Chip 
                                label={`${parseFloat(transaction.montant).toFixed(2)} €`}
                                color={transaction.compte_debit_id === parseInt(id) ? 'primary' : 'secondary'}
                                variant="outlined"
                                size="small"
                              />
                            </ListItemSecondaryAction>
                          </ListItem>
                          <Divider component="li" />
                        </React.Fragment>
                      ))}
                    </List>
                  )}
                </>
              )}
              
              {tabValue === 1 && (
                <Typography variant="body2" color="text.secondary" align="center" sx={{ py: 3 }}>
                  Historique des modifications non disponible
                </Typography>
              )}
            </Box>
          </Paper>
        </Grid>
      </Grid>
    </Box>
  );
};

export default CompteDetail;
