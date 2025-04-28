import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  TextField, Button, Typography, Container, Box, Alert, Paper,
  Card, CardContent, Divider, Grid, Link
} from '@mui/material';
import { AccountCircle, Lock, Info } from '@mui/icons-material';
import authService from '../../services/auth.service';

const Login = () => {
  const [email, setEmail] = useState('admin@example.com');
  const [password, setPassword] = useState('password');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    // Vérifier si l'utilisateur vient de se déconnecter
    const justLoggedOut = localStorage.getItem('justLoggedOut');
    if (justLoggedOut) {
      setSuccess('Vous avez été déconnecté avec succès.');
      localStorage.removeItem('justLoggedOut');
    }

    // Vérifier si l'utilisateur est déjà connecté
    if (authService.isAuthenticated()) {
      navigate('/dashboard');
    }
  }, [navigate]);

  const handleLogin = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    try {
      await authService.login(email, password);
      navigate('/dashboard');
    } catch (err) {
      setError(err.response?.data?.message || 'Une erreur est survenue lors de la connexion');
    } finally {
      setLoading(false);
    }
  };

  const handleDemoLogin = (role) => {
    let demoEmail = 'admin@example.com';
    if (role === 'comptable') {
      demoEmail = 'comptable@example.com';
    } else if (role === 'user') {
      demoEmail = 'user@example.com';
    }

    setEmail(demoEmail);
    setPassword('password');
  };

  return (
    <Container maxWidth="md" sx={{ py: 8 }}>
      <Grid container spacing={4}>
        <Grid item xs={12} md={6}>
          <Box sx={{ display: 'flex', flexDirection: 'column', height: '100%' }}>
            <Typography variant="h3" component="h1" gutterBottom color="primary" fontWeight="bold">
              API Comptable
            </Typography>

            <Typography variant="h5" component="h2" gutterBottom color="text.secondary">
              Système de gestion comptable
            </Typography>

            <Typography variant="body1" paragraph sx={{ mt: 2 }}>
              Bienvenue dans l'application de gestion comptable. Cette interface vous permet de gérer vos comptes, transactions, et de consulter votre journal comptable.
            </Typography>

            <Card sx={{ mt: 'auto', bgcolor: 'primary.light', color: 'white' }}>
              <CardContent>
                <Typography variant="h6" gutterBottom>
                  <Info sx={{ mr: 1, verticalAlign: 'middle' }} />
                  Comptes de démonstration
                </Typography>

                <Divider sx={{ my: 1, bgcolor: 'white', opacity: 0.2 }} />

                <Box sx={{ mt: 2 }}>
                  <Typography variant="body2" paragraph>
                    Utilisez l'un des comptes suivants pour vous connecter :
                  </Typography>

                  <Grid container spacing={2}>
                    <Grid item xs={12}>
                      <Button
                        variant="outlined"
                        fullWidth
                        sx={{ color: 'white', borderColor: 'white' }}
                        onClick={() => handleDemoLogin('admin')}
                      >
                        Administrateur
                      </Button>
                    </Grid>

                    <Grid item xs={12}>
                      <Button
                        variant="outlined"
                        fullWidth
                        sx={{ color: 'white', borderColor: 'white' }}
                        onClick={() => handleDemoLogin('comptable')}
                      >
                        Comptable
                      </Button>
                    </Grid>

                    <Grid item xs={12}>
                      <Button
                        variant="outlined"
                        fullWidth
                        sx={{ color: 'white', borderColor: 'white' }}
                        onClick={() => handleDemoLogin('user')}
                      >
                        Utilisateur
                      </Button>
                    </Grid>
                  </Grid>
                </Box>
              </CardContent>
            </Card>
          </Box>
        </Grid>

        <Grid item xs={12} md={6}>
          <Paper elevation={3} sx={{ p: 4, height: '100%' }}>
            <Typography component="h2" variant="h4" align="center" gutterBottom>
              Connexion
            </Typography>

            {error && <Alert severity="error" sx={{ mt: 2, mb: 2 }}>{error}</Alert>}
            {success && <Alert severity="success" sx={{ mt: 2, mb: 2 }}>{success}</Alert>}

            <Box component="form" onSubmit={handleLogin} sx={{ mt: 4 }}>
              <Box sx={{ display: 'flex', alignItems: 'flex-end', mb: 3 }}>
                <AccountCircle sx={{ color: 'action.active', mr: 1, my: 0.5 }} />
                <TextField
                  required
                  fullWidth
                  id="email"
                  label="Adresse email"
                  name="email"
                  autoComplete="email"
                  autoFocus
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  variant="standard"
                />
              </Box>

              <Box sx={{ display: 'flex', alignItems: 'flex-end', mb: 4 }}>
                <Lock sx={{ color: 'action.active', mr: 1, my: 0.5 }} />
                <TextField
                  required
                  fullWidth
                  name="password"
                  label="Mot de passe"
                  type="password"
                  id="password"
                  autoComplete="current-password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  variant="standard"
                />
              </Box>

              <Button
                type="submit"
                fullWidth
                variant="contained"
                size="large"
                sx={{ mt: 3, mb: 2, py: 1.5 }}
                disabled={loading}
              >
                {loading ? 'Connexion en cours...' : 'Se connecter'}
              </Button>

              <Box sx={{ mt: 4, textAlign: 'center' }}>
                <Typography variant="body2" color="text.secondary">
                  Mot de passe pour tous les comptes : <strong>password</strong>
                </Typography>
              </Box>
            </Box>
          </Paper>
        </Grid>
      </Grid>
    </Container>
  );
};

export default Login;
