import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { 
  Typography, Box, Paper, Grid, Divider, Button, 
  Chip, Alert, CircularProgress, Card, CardContent
} from '@mui/material';
import { ArrowBack, Person, CalendarToday, Description } from '@mui/icons-material';
import transactionService from '../../services/transaction.service';

const TransactionDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  
  const [transaction, setTransaction] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchTransaction();
  }, [id]);

  const fetchTransaction = async () => {
    try {
      setLoading(true);
      const response = await transactionService.getTransactionById(id);
      setTransaction(response.data.data);
    } catch (err) {
      setError('Erreur lors du chargement de la transaction');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
  };

  if (loading) {
    return (
      <Box sx={{ display: 'flex', justifyContent: 'center', mt: 4 }}>
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return (
      <Box>
        <Alert severity="error" sx={{ mb: 2 }}>
          {error}
        </Alert>
        <Button 
          startIcon={<ArrowBack />} 
          onClick={() => navigate('/transactions')}
        >
          Retour à la liste des transactions
        </Button>
      </Box>
    );
  }

  if (!transaction) {
    return (
      <Box>
        <Alert severity="warning" sx={{ mb: 2 }}>
          Transaction non trouvée
        </Alert>
        <Button 
          startIcon={<ArrowBack />} 
          onClick={() => navigate('/transactions')}
        >
          Retour à la liste des transactions
        </Button>
      </Box>
    );
  }

  return (
    <Box>
      <Box sx={{ display: 'flex', alignItems: 'center', mb: 3 }}>
        <Button 
          startIcon={<ArrowBack />} 
          onClick={() => navigate('/transactions')}
          sx={{ mr: 2 }}
        >
          Retour
        </Button>
        <Typography variant="h5">
          Détails de la transaction #{transaction.id}
        </Typography>
      </Box>
      
      <Paper sx={{ p: 3, mb: 3 }}>
        <Grid container spacing={3}>
          <Grid item xs={12} md={6}>
            <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
              <CalendarToday sx={{ mr: 1, color: 'primary.main' }} />
              <Typography variant="h6">
                Date: {formatDate(transaction.date)}
              </Typography>
            </Box>
            
            <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
              <Description sx={{ mr: 1, color: 'primary.main' }} />
              <Typography variant="h6">
                Description: {transaction.description}
              </Typography>
            </Box>
            
            <Box sx={{ display: 'flex', alignItems: 'center' }}>
              <Typography variant="h6" color="primary" sx={{ mr: 1 }}>
                Montant:
              </Typography>
              <Typography variant="h5" fontWeight="bold">
                {parseFloat(transaction.montant).toFixed(2)} €
              </Typography>
            </Box>
          </Grid>
          
          <Grid item xs={12} md={6}>
            {transaction.entree_journal && transaction.entree_journal.utilisateur && (
              <Box sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                <Person sx={{ mr: 1, color: 'primary.main' }} />
                <Typography variant="body1">
                  Créée par: {transaction.entree_journal.utilisateur.nom} {transaction.entree_journal.utilisateur.prenom}
                </Typography>
              </Box>
            )}
            
            <Typography variant="body1" color="text.secondary">
              Créée le: {transaction.created_at ? new Date(transaction.created_at).toLocaleString() : 'N/A'}
            </Typography>
            
            <Typography variant="body1" color="text.secondary">
              Dernière modification: {transaction.updated_at ? new Date(transaction.updated_at).toLocaleString() : 'N/A'}
            </Typography>
          </Grid>
        </Grid>
        
        <Divider sx={{ my: 3 }} />
        
        <Typography variant="h6" gutterBottom>
          Écritures comptables
        </Typography>
        
        <Grid container spacing={3}>
          <Grid item xs={12} md={6}>
            <Card variant="outlined" sx={{ bgcolor: 'primary.light', color: 'white' }}>
              <CardContent>
                <Typography variant="h6" gutterBottom>
                  Débit
                </Typography>
                
                <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mb: 1 }}>
                  <Typography variant="body1">
                    Compte:
                  </Typography>
                  <Chip 
                    label={`${transaction.compte_debit?.code} - ${transaction.compte_debit?.nom}`} 
                    color="primary"
                    variant="outlined"
                    sx={{ bgcolor: 'white' }}
                  />
                </Box>
                
                <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <Typography variant="body1">
                    Montant:
                  </Typography>
                  <Typography variant="h6" fontWeight="bold">
                    {parseFloat(transaction.montant).toFixed(2)} €
                  </Typography>
                </Box>
              </CardContent>
            </Card>
          </Grid>
          
          <Grid item xs={12} md={6}>
            <Card variant="outlined" sx={{ bgcolor: 'secondary.light', color: 'white' }}>
              <CardContent>
                <Typography variant="h6" gutterBottom>
                  Crédit
                </Typography>
                
                <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mb: 1 }}>
                  <Typography variant="body1">
                    Compte:
                  </Typography>
                  <Chip 
                    label={`${transaction.compte_credit?.code} - ${transaction.compte_credit?.nom}`} 
                    color="secondary"
                    variant="outlined"
                    sx={{ bgcolor: 'white' }}
                  />
                </Box>
                
                <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <Typography variant="body1">
                    Montant:
                  </Typography>
                  <Typography variant="h6" fontWeight="bold">
                    {parseFloat(transaction.montant).toFixed(2)} €
                  </Typography>
                </Box>
              </CardContent>
            </Card>
          </Grid>
        </Grid>
      </Paper>
    </Box>
  );
};

export default TransactionDetail;
