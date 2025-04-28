import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { 
  Table, TableBody, TableCell, TableContainer, TableHead, TableRow, 
  Paper, Typography, Box, Chip, Alert, CircularProgress
} from '@mui/material';
import journalService from '../../services/journal.service';

const JournalList = () => {
  const [entries, setEntries] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchJournalEntries();
  }, []);

  const fetchJournalEntries = async () => {
    try {
      setLoading(true);
      const response = await journalService.getJournalEntries();
      setEntries(response.data.data);
      setError('');
    } catch (err) {
      setError('Erreur lors du chargement du journal');
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

  return (
    <Box>
      {error && (
        <Alert severity="error" sx={{ mb: 2 }} onClose={() => setError('')}>
          {error}
        </Alert>
      )}
      
      <Typography variant="h5" sx={{ mb: 3 }}>
        Journal comptable
      </Typography>
      
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
              <TableCell>Utilisateur</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {entries.map((entry) => (
              <TableRow key={entry.id}>
                <TableCell>
                  <Link to={`/transactions/${entry.transaction?.id}`} style={{ textDecoration: 'none' }}>
                    {entry.transaction?.id}
                  </Link>
                </TableCell>
                <TableCell>{entry.transaction?.date ? formatDate(entry.transaction.date) : 'N/A'}</TableCell>
                <TableCell>{entry.transaction?.description}</TableCell>
                <TableCell>
                  {entry.transaction?.compte_debit && (
                    <Chip 
                      label={`${entry.transaction.compte_debit.code} - ${entry.transaction.compte_debit.nom}`} 
                      size="small" 
                      variant="outlined"
                    />
                  )}
                </TableCell>
                <TableCell>
                  {entry.transaction?.compte_credit && (
                    <Chip 
                      label={`${entry.transaction.compte_credit.code} - ${entry.transaction.compte_credit.nom}`} 
                      size="small" 
                      variant="outlined"
                    />
                  )}
                </TableCell>
                <TableCell align="right">
                  {entry.transaction?.montant ? `${parseFloat(entry.transaction.montant).toFixed(2)} €` : 'N/A'}
                </TableCell>
                <TableCell>
                  {entry.utilisateur ? `${entry.utilisateur.nom} ${entry.utilisateur.prenom}` : 'N/A'}
                </TableCell>
              </TableRow>
            ))}
            {entries.length === 0 && (
              <TableRow>
                <TableCell colSpan={7} align="center">
                  Aucune entrée dans le journal
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </TableContainer>
    </Box>
  );
};

export default JournalList;
