import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { 
  Table, TableBody, TableCell, TableContainer, TableHead, TableRow, 
  Paper, Button, Typography, Box, IconButton, Alert,
  Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle,
  Chip
} from '@mui/material';
import { Add, Visibility, Delete } from '@mui/icons-material';
import transactionService from '../../services/transaction.service';
import authService from '../../services/auth.service';

const TransactionList = () => {
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [transactionToDelete, setTransactionToDelete] = useState(null);
  const [successMessage, setSuccessMessage] = useState('');
  
  const isComptable = authService.isComptable();
  const isAdmin = authService.isAdmin();

  useEffect(() => {
    fetchTransactions();
  }, []);

  const fetchTransactions = async () => {
    try {
      setLoading(true);
      const response = await transactionService.getAllTransactions();
      setTransactions(response.data.data);
      setError('');
    } catch (err) {
      setError('Erreur lors du chargement des transactions');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteClick = (transaction) => {
    setTransactionToDelete(transaction);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!transactionToDelete) return;
    
    try {
      await transactionService.deleteTransaction(transactionToDelete.id);
      setTransactions(transactions.filter(t => t.id !== transactionToDelete.id));
      setSuccessMessage(`La transaction #${transactionToDelete.id} a été supprimée avec succès.`);
      setDeleteDialogOpen(false);
      setTransactionToDelete(null);
    } catch (err) {
      setError(err.response?.data?.message || 'Erreur lors de la suppression de la transaction');
      setDeleteDialogOpen(false);
    }
  };

  const handleDeleteCancel = () => {
    setDeleteDialogOpen(false);
    setTransactionToDelete(null);
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
  };

  if (loading) return <Typography>Chargement...</Typography>;

  return (
    <Box>
      {successMessage && (
        <Alert severity="success" sx={{ mb: 2 }} onClose={() => setSuccessMessage('')}>
          {successMessage}
        </Alert>
      )}
      
      {error && (
        <Alert severity="error" sx={{ mb: 2 }} onClose={() => setError('')}>
          {error}
        </Alert>
      )}
      
      <Box sx={{ display: 'flex', justifyContent: 'space-between', mb: 2 }}>
        <Typography variant="h5">Liste des transactions</Typography>
        {isComptable && (
          <Button 
            component={Link} 
            to="/transactions/new" 
            variant="contained" 
            startIcon={<Add />}
          >
            Nouvelle transaction
          </Button>
        )}
      </Box>
      
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>ID</TableCell>
              <TableCell>Date</TableCell>
              <TableCell>Description</TableCell>
              <TableCell>Compte débité</TableCell>
              <TableCell>Compte crédité</TableCell>
              <TableCell align="right">Montant</TableCell>
              <TableCell align="center">Actions</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {transactions.map((transaction) => (
              <TableRow key={transaction.id}>
                <TableCell>{transaction.id}</TableCell>
                <TableCell>{formatDate(transaction.date)}</TableCell>
                <TableCell>{transaction.description}</TableCell>
                <TableCell>
                  <Chip 
                    label={`${transaction.compte_debit?.code} - ${transaction.compte_debit?.nom}`} 
                    size="small" 
                    variant="outlined"
                  />
                </TableCell>
                <TableCell>
                  <Chip 
                    label={`${transaction.compte_credit?.code} - ${transaction.compte_credit?.nom}`} 
                    size="small" 
                    variant="outlined"
                  />
                </TableCell>
                <TableCell align="right">{parseFloat(transaction.montant).toFixed(2)} €</TableCell>
                <TableCell align="center">
                  <IconButton 
                    component={Link} 
                    to={`/transactions/${transaction.id}`}
                    color="primary"
                    size="small"
                  >
                    <Visibility />
                  </IconButton>
                  {isAdmin && (
                    <IconButton 
                      onClick={() => handleDeleteClick(transaction)}
                      color="error"
                      size="small"
                    >
                      <Delete />
                    </IconButton>
                  )}
                </TableCell>
              </TableRow>
            ))}
            {transactions.length === 0 && (
              <TableRow>
                <TableCell colSpan={7} align="center">
                  Aucune transaction trouvée
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Dialog de confirmation de suppression */}
      <Dialog
        open={deleteDialogOpen}
        onClose={handleDeleteCancel}
      >
        <DialogTitle>Confirmer la suppression</DialogTitle>
        <DialogContent>
          <DialogContentText>
            Êtes-vous sûr de vouloir supprimer la transaction #{transactionToDelete?.id} ?
            Cette action est irréversible et annulera les effets de cette transaction sur les soldes des comptes.
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDeleteCancel} color="primary">
            Annuler
          </Button>
          <Button onClick={handleDeleteConfirm} color="error" autoFocus>
            Supprimer
          </Button>
        </DialogActions>
      </Dialog>
    </Box>
  );
};

export default TransactionList;
