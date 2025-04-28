import React from 'react';
import { 
  Box, Typography, Paper, Divider, Chip, 
  List, ListItem, ListItemText, ListItemSecondaryAction,
  Button
} from '@mui/material';
import { Link } from 'react-router-dom';
import { Receipt } from '@mui/icons-material';

const RecentTransactions = ({ transactions = [], limit = 5 }) => {
  if (!transactions || transactions.length === 0) {
    return (
      <Paper sx={{ p: 3 }}>
        <Typography variant="h6" gutterBottom>
          Transactions récentes
        </Typography>
        <Divider sx={{ mb: 2 }} />
        <Typography variant="body2" color="text.secondary" align="center" sx={{ py: 3 }}>
          Aucune transaction récente
        </Typography>
      </Paper>
    );
  }

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
  };

  const displayedTransactions = transactions.slice(0, limit);

  return (
    <Paper sx={{ p: 3 }}>
      <Typography variant="h6" gutterBottom>
        Transactions récentes
      </Typography>
      <Divider sx={{ mb: 2 }} />
      
      <List disablePadding>
        {displayedTransactions.map((transaction) => (
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
              <Box sx={{ mr: 2, color: 'primary.main' }}>
                <Receipt />
              </Box>
              
              <ListItemText
                primary={transaction.description}
                secondary={`${formatDate(transaction.date)} • ${transaction.compte_debit?.code} → ${transaction.compte_credit?.code}`}
              />
              
              <ListItemSecondaryAction>
                <Chip 
                  label={`${parseFloat(transaction.montant).toFixed(2)} €`}
                  color="primary"
                  variant="outlined"
                  size="small"
                />
              </ListItemSecondaryAction>
            </ListItem>
            <Divider component="li" />
          </React.Fragment>
        ))}
      </List>
      
      {transactions.length > limit && (
        <Box sx={{ mt: 2, textAlign: 'center' }}>
          <Button 
            component={Link} 
            to="/transactions" 
            variant="outlined"
            size="small"
          >
            Voir toutes les transactions
          </Button>
        </Box>
      )}
    </Paper>
  );
};

export default RecentTransactions;
