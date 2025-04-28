import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { 
  TextField, Button, Typography, Box, Paper, 
  FormControl, InputLabel, Select, MenuItem, 
  Grid, Alert, CircularProgress
} from '@mui/material';
import { ArrowBack, Save } from '@mui/icons-material';
import compteService from '../../services/compte.service';

const CompteForm = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEditMode = !!id;
  
  const [formData, setFormData] = useState({
    nom: '',
    code: '',
    type: '',
    solde: 0
  });
  
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [validationErrors, setValidationErrors] = useState({});

  useEffect(() => {
    if (isEditMode) {
      fetchCompte();
    }
  }, [id]);

  const fetchCompte = async () => {
    try {
      setLoading(true);
      const response = await compteService.getCompteById(id);
      const compte = response.data.data;
      setFormData({
        nom: compte.nom,
        code: compte.code,
        type: compte.type,
        solde: compte.solde
      });
    } catch (err) {
      setError('Erreur lors du chargement du compte');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
    
    // Effacer l'erreur de validation pour ce champ
    if (validationErrors[name]) {
      setValidationErrors({
        ...validationErrors,
        [name]: ''
      });
    }
  };

  const validateForm = () => {
    const errors = {};
    
    if (!formData.nom) {
      errors.nom = 'Le nom est requis';
    }
    
    if (!formData.code) {
      errors.code = 'Le code est requis';
    } else if (!/^\d+$/.test(formData.code)) {
      errors.code = 'Le code doit contenir uniquement des chiffres';
    }
    
    if (!formData.type) {
      errors.type = 'Le type est requis';
    }
    
    if (formData.solde === '') {
      errors.solde = 'Le solde est requis';
    } else if (isNaN(parseFloat(formData.solde))) {
      errors.solde = 'Le solde doit être un nombre';
    }
    
    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }
    
    try {
      setSaving(true);
      
      const data = {
        ...formData,
        solde: parseFloat(formData.solde)
      };
      
      if (isEditMode) {
        await compteService.updateCompte(id, data);
      } else {
        await compteService.createCompte(data);
      }
      
      navigate('/comptes');
    } catch (err) {
      if (err.response && err.response.data && err.response.data.errors) {
        setValidationErrors(err.response.data.errors);
      } else {
        setError(err.response?.data?.message || 'Une erreur est survenue');
      }
      console.error(err);
    } finally {
      setSaving(false);
    }
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
      <Box sx={{ display: 'flex', alignItems: 'center', mb: 3 }}>
        <Button 
          startIcon={<ArrowBack />} 
          onClick={() => navigate('/comptes')}
          sx={{ mr: 2 }}
        >
          Retour
        </Button>
        <Typography variant="h5">
          {isEditMode ? 'Modifier le compte' : 'Nouveau compte'}
        </Typography>
      </Box>
      
      {error && (
        <Alert severity="error" sx={{ mb: 2 }} onClose={() => setError('')}>
          {error}
        </Alert>
      )}
      
      <Paper sx={{ p: 3 }}>
        <Box component="form" onSubmit={handleSubmit}>
          <Grid container spacing={2}>
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Nom du compte"
                name="nom"
                value={formData.nom}
                onChange={handleChange}
                margin="normal"
                error={!!validationErrors.nom}
                helperText={validationErrors.nom}
                required
              />
            </Grid>
            
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Code du compte"
                name="code"
                value={formData.code}
                onChange={handleChange}
                margin="normal"
                error={!!validationErrors.code}
                helperText={validationErrors.code}
                required
              />
            </Grid>
            
            <Grid item xs={12} md={6}>
              <FormControl fullWidth margin="normal" error={!!validationErrors.type} required>
                <InputLabel>Type de compte</InputLabel>
                <Select
                  name="type"
                  value={formData.type}
                  onChange={handleChange}
                  label="Type de compte"
                >
                  <MenuItem value="actif">Actif</MenuItem>
                  <MenuItem value="passif">Passif</MenuItem>
                  <MenuItem value="produit">Produit</MenuItem>
                  <MenuItem value="charge">Charge</MenuItem>
                </Select>
                {validationErrors.type && (
                  <Typography variant="caption" color="error">
                    {validationErrors.type}
                  </Typography>
                )}
              </FormControl>
            </Grid>
            
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Solde"
                name="solde"
                type="number"
                value={formData.solde}
                onChange={handleChange}
                margin="normal"
                error={!!validationErrors.solde}
                helperText={validationErrors.solde}
                InputProps={{
                  endAdornment: '€'
                }}
              />
            </Grid>
          </Grid>
          
          <Box sx={{ mt: 3, display: 'flex', justifyContent: 'flex-end' }}>
            <Button 
              variant="outlined" 
              onClick={() => navigate('/comptes')}
              sx={{ mr: 1 }}
            >
              Annuler
            </Button>
            <Button 
              type="submit" 
              variant="contained" 
              startIcon={<Save />}
              disabled={saving}
            >
              {saving ? 'Enregistrement...' : 'Enregistrer'}
            </Button>
          </Box>
        </Box>
      </Paper>
    </Box>
  );
};

export default CompteForm;
