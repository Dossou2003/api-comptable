import React from 'react';
import { Box, Typography, Button, Paper } from '@mui/material';
import { Link } from 'react-router-dom';
import { Add } from '@mui/icons-material';

const EmptyState = ({ 
  title = 'Aucune donnée', 
  description = 'Aucune donnée disponible pour le moment.', 
  actionText = 'Ajouter', 
  actionLink = '#',
  showAction = true,
  icon: Icon
}) => {
  return (
    <Paper sx={{ p: 4, textAlign: 'center', mt: 2 }}>
      {Icon && <Icon sx={{ fontSize: 60, color: 'text.secondary', mb: 2 }} />}
      
      <Typography variant="h5" gutterBottom>
        {title}
      </Typography>
      
      <Typography variant="body1" color="text.secondary" paragraph>
        {description}
      </Typography>
      
      {showAction && (
        <Button 
          component={Link} 
          to={actionLink} 
          variant="contained" 
          startIcon={<Add />}
        >
          {actionText}
        </Button>
      )}
    </Paper>
  );
};

export default EmptyState;
