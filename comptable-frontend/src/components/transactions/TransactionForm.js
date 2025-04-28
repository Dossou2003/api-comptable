import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { 
  TextField, Button, Typography, Box, Paper, 
  FormControl, InputLabel, Select, MenuItem, 
  Grid, Alert, CircularProgress, Autocomplete
} from '@mui/material';
import { ArrowBack, Save } from '@mui/icons-material';
import transactionService from '../../services/transaction.service';
import compteService from '../../services/compte.service';
import authService from '../../services/auth.service';

const TransactionForm = () => {
  const navigate = useNavigate();
  const isComptable = authService.isComptable();
  
  const [formData, setFormData] = useState({
    date: new Date().toISOString().split('T')[0],
    description: '',
    compte_debit_id: '',
    compte_credit_id: '',
    montant: ''
  });
  
  const [comptes, setComptes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [validationErrors, setValidationErrors] = useState({});

  useEffect(() => {
    if (!isComptable) {
      setError('Vous n\'avez pas les droits nécessaires pour créer une transaction');
      return;
    }
    
    fetchComptes();
  }, [isComptable]);

  const fetchComptes = async () => {
    try {
      setLoading(true);
      const response = await compteService.getAllComptes();
      setComptes(response.data.data);
    } catch (err) {
      setError('Erreur lors du chargement des comptes');
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

  const handleCompteChange = (name, value) => {
    setFormData({
      ...formData,
      [name]: value ? value.id : ''
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
    
    if (!formData.date) {
      errors.date = 'La date est requise';
    }
    
    if (!formData.description) {
      errors.description = 'La description est requise';
    }
    
    if (!formData.compte_debit_id) {
      errors.compte_debit_id = 'Le compte débité est requis';
    }
    
    if (!formData.compte_credit_id) {
      errors.compte_credit_id = 'Le compte crédité est requis';
    }
    
    if (formData.compte_debit_id && formData.compte_credit_id && 
        formData.compte_debit_id === formData.compte_credit_id) {
      errors.compte_credit_id = 'Le compte crédité doit être différent du compte débité';
    }
    
    if (!formData.montant) {
      errors.montant = 'Le montant est requis';
    } else if (isNaN(parseFloat(formData.montant)) || parseFloat(formData.montant) <= 0) {
      errors.montant = 'Le montant doit être un nombre positif';
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
        montant: parseFloat(formData.montant)
      };
      
      await transactionService.createTransaction(data);
      navigate('/transactions');
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

  if (!isComptable) {
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
          Nouvelle transaction
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
                label="Date"
                name="date"
                type="date"
                value={formData.date}
                onChange={handleChange}
                margin="normal"
                error={!!validationErrors.date}
                helperText={validationErrors.date}
                required
                InputLabelProps={{
                  shrink: true,
                }}
              />
            </Grid>
            
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Montant"
                name="montant"
                type="number"
                value={formData.montant}
                onChange={handleChange}
                margin="normal"
                error={!!validationErrors.montant}
                helperText={validationErrors.montant}
                required
                InputProps={{
                  endAdornment: '€',
                  inputProps: { min: 0, step: 0.01 }
                }}
              />
            </Grid>
            
            <Grid item xs={12}>
              <TextField
                fullWidth
                label="Description"
                name="description"
                value={formData.description}
                onChange={handleChange}
                margin="normal"
                error={!!validationErrors.description}
                helperText={validationErrors.description}
                required
              />
            </Grid>
            
            <Grid item xs={12} md={6}>
              <Autocomplete
                options={comptes}
                getOptionLabel={(option) => `${option.code} - ${option.nom}`}
                value={comptes.find(c => c.id === formData.compte_debit_id) || null}
                onChange={(event, newValue) => handleCompteChange('compte_debit_id', newValue)}
                renderInput={(params) => (
                  <TextField
                    {...params}
                    label="Compte débité"
                    margin="normal"
                    error={!!validationErrors.compte_debit_id}
                    helperText={validationErrors.compte_debit_id}
                    required
                  />
                )}
              />
            </Grid>
            
            <Grid item xs={12} md={6}>
              <Autocomplete
                options={comptes}
                getOptionLabel={(option) => `${option.code} - ${option.nom}`}
                value={comptes.find(c => c.id === formData.compte_credit_id) || null}
                onChange={(event, newValue) => handleCompteChange('compte_credit_id', newValue)}
                renderInput={(params) => (
                  <TextField
                    {...params}
                    label="Compte crédité"
                    margin="normal"
                    error={!!validationErrors.compte_credit_id}
                    helperText={validationErrors.compte_credit_id}
                    required
                  />
                )}
              />
            </Grid>
          </Grid>
          
          <Box sx={{ mt: 3, display: 'flex', justifyContent: 'flex-end' }}>
            <Button 
              variant="outlined" 
              onClick={() => navigate('/transactions')}
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

export default TransactionForm;
