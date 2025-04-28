import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { 
  Table, TableBody, TableCell, TableContainer, TableHead, TableRow, 
  Paper, Button, Typography, Box, IconButton, Chip, Alert,
  Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle
} from '@mui/material';
import { Edit, Delete, Add } from '@mui/icons-material';
import compteService from '../../services/compte.service';
import authService from '../../services/auth.service';

const CompteList = () => {
  const [comptes, setComptes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [compteToDelete, setCompteToDelete] = useState(null);
  const [successMessage, setSuccessMessage] = useState('');
  const isAdmin = authService.isAdmin();

  useEffect(() => {
    fetchComptes();
  }, []);

  const fetchComptes = async () => {
    try {
      setLoading(true);
      const response = await compteService.getAllComptes();
      setComptes(response.data.data);
      setError('');
    } catch (err) {
      setError('Erreur lors du chargement des comptes');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteClick = (compte) => {
    setCompteToDelete(compte);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!compteToDelete) return;
    
    try {
      await compteService.deleteCompte(compteToDelete.id);
      setComptes(comptes.filter(c => c.id !== compteToDelete.id));
      setSuccessMessage(`Le compte ${compteToDelete.code} - ${compteToDelete.nom} a été supprimé avec succès.`);
      setDeleteDialogOpen(false);
      setCompteToDelete(null);
    } catch (err) {
      setError(err.response?.data?.message || 'Erreur lors de la suppression du compte');
      setDeleteDialogOpen(false);
    }
  };

  const handleDeleteCancel = () => {
    setDeleteDialogOpen(false);
    setCompteToDelete(null);
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
        size="small" 
        variant="outlined"
      />
    );
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
        <Typography variant="h5">Liste des comptes</Typography>
        <Button 
          component={Link} 
          to="/comptes/new" 
          variant="contained" 
          startIcon={<Add />}
        >
          Nouveau compte
        </Button>
      </Box>
      
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Code</TableCell>
              <TableCell>Nom</TableCell>
              <TableCell>Type</TableCell>
              <TableCell align="right">Solde</TableCell>
              <TableCell align="center">Actions</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {comptes.map((compte) => (
              <TableRow key={compte.id}>
                <TableCell>{compte.code}</TableCell>
                <TableCell>{compte.nom}</TableCell>
                <TableCell>{getTypeChip(compte.type)}</TableCell>
                <TableCell align="right">{parseFloat(compte.solde).toFixed(2)} €</TableCell>
                <TableCell align="center">
                  <IconButton 
                    component={Link} 
                    to={`/comptes/${compte.id}/edit`}
                    color="primary"
                    size="small"
                  >
                    <Edit />
                  </IconButton>
                  {isAdmin && (
                    <IconButton 
                      onClick={() => handleDeleteClick(compte)}
                      color="error"
                      size="small"
                    >
                      <Delete />
                    </IconButton>
                  )}
                </TableCell>
              </TableRow>
            ))}
            {comptes.length === 0 && (
              <TableRow>
                <TableCell colSpan={5} align="center">
                  Aucun compte trouvé
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
            Êtes-vous sûr de vouloir supprimer le compte {compteToDelete?.code} - {compteToDelete?.nom} ?
            Cette action est irréversible.
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

export default CompteList;
