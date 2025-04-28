import React, { useState, useEffect } from 'react';
import {
  Typography, Box, Paper, Button, Table, TableBody,
  TableCell, TableContainer, TableHead, TableRow,
  Alert, CircularProgress, Grid, Card, CardContent
} from '@mui/material';
import {
  FileDownload, TableChart, Description,
  BarChart, PieChart
} from '@mui/icons-material';
import balanceService from '../../services/balance.service';

const BalanceExport = () => {
  const [comptes, setComptes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [exporting, setExporting] = useState(false);
  const [error, setError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');

  useEffect(() => {
    fetchBalanceData();
  }, []);

  const fetchBalanceData = async () => {
    try {
      setLoading(true);
      const response = await balanceService.getBalanceData();
      setComptes(response.data.data.comptes);
      setError('');
    } catch (err) {
      setError('Erreur lors du chargement de la balance');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleExportExcel = async () => {
    try {
      setExporting(true);
      const response = await balanceService.exportExcel();

      // Créer un URL pour le blob
      const url = window.URL.createObjectURL(new Blob([response.data]));

      // Créer un lien temporaire et cliquer dessus pour télécharger
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `balance_comptable_${new Date().toISOString().split('T')[0]}.html`);
      document.body.appendChild(link);
      link.click();

      // Nettoyer
      link.parentNode.removeChild(link);
      window.URL.revokeObjectURL(url);

      setSuccessMessage('La balance a été exportée avec succès au format Excel');
    } catch (err) {
      setError('Erreur lors de l\'export de la balance');
      console.error(err);
    } finally {
      setExporting(false);
    }
  };

  const handleExportCsv = async () => {
    try {
      setExporting(true);
      const response = await balanceService.exportCsv();

      // Créer un URL pour le blob
      const url = window.URL.createObjectURL(new Blob([response.data]));

      // Créer un lien temporaire et cliquer dessus pour télécharger
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `balance_comptable_${new Date().toISOString().split('T')[0]}.csv`);
      document.body.appendChild(link);
      link.click();

      // Nettoyer
      link.parentNode.removeChild(link);
      window.URL.revokeObjectURL(url);

      setSuccessMessage('La balance a été exportée avec succès au format CSV');
    } catch (err) {
      setError('Erreur lors de l\'export de la balance');
      console.error(err);
    } finally {
      setExporting(false);
    }
  };

  // Calculer les totaux
  const calculateTotals = () => {
    const totals = {
      actif: 0,
      passif: 0,
      produit: 0,
      charge: 0,
      debit: 0,
      credit: 0,
      solde: 0
    };

    comptes.forEach(compte => {
      totals[compte.type] += parseFloat(compte.solde);
      totals.solde += parseFloat(compte.solde);

      // Simuler les totaux débit/crédit (dans une vraie application, ces valeurs viendraient de l'API)
      if (compte.type === 'actif' || compte.type === 'charge') {
        totals.debit += parseFloat(compte.solde);
      } else {
        totals.credit += parseFloat(compte.solde);
      }
    });

    return totals;
  };

  const totals = calculateTotals();

  if (loading) {
    return (
      <Box sx={{ display: 'flex', justifyContent: 'center', mt: 4 }}>
        <CircularProgress />
      </Box>
    );
  }

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

      <Typography variant="h5" sx={{ mb: 3 }}>
        Balance comptable
      </Typography>

      <Grid container spacing={3} sx={{ mb: 3 }}>
        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box sx={{ display: 'flex', alignItems: 'center', mb: 1 }}>
                <BarChart color="primary" sx={{ mr: 1 }} />
                <Typography variant="h6">
                  Total Actif
                </Typography>
              </Box>
              <Typography variant="h4" color="primary">
                {totals.actif.toFixed(2)} €
              </Typography>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box sx={{ display: 'flex', alignItems: 'center', mb: 1 }}>
                <BarChart color="secondary" sx={{ mr: 1 }} />
                <Typography variant="h6">
                  Total Passif
                </Typography>
              </Box>
              <Typography variant="h4" color="secondary">
                {totals.passif.toFixed(2)} €
              </Typography>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box sx={{ display: 'flex', alignItems: 'center', mb: 1 }}>
                <PieChart color="success" sx={{ mr: 1 }} />
                <Typography variant="h6">
                  Total Produits
                </Typography>
              </Box>
              <Typography variant="h4" color="success.main">
                {totals.produit.toFixed(2)} €
              </Typography>
            </CardContent>
          </Card>
        </Grid>

        <Grid item xs={12} md={3}>
          <Card>
            <CardContent>
              <Box sx={{ display: 'flex', alignItems: 'center', mb: 1 }}>
                <PieChart color="error" sx={{ mr: 1 }} />
                <Typography variant="h6">
                  Total Charges
                </Typography>
              </Box>
              <Typography variant="h4" color="error">
                {totals.charge.toFixed(2)} €
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>

      <Paper sx={{ p: 3, mb: 3 }}>
        <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mb: 2 }}>
          <Typography variant="h6">
            <TableChart sx={{ mr: 1, verticalAlign: 'middle' }} />
            Balance des comptes
          </Typography>

          <Box>
            <Button
              variant="contained"
              startIcon={<FileDownload />}
              onClick={handleExportExcel}
              disabled={exporting}
              sx={{ mr: 1 }}
            >
              Exporter en Excel
            </Button>

            <Button
              variant="outlined"
              startIcon={<Description />}
              onClick={handleExportCsv}
              disabled={exporting}
            >
              Exporter en CSV
            </Button>
          </Box>
        </Box>

        <TableContainer>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell>Code</TableCell>
                <TableCell>Nom</TableCell>
                <TableCell>Type</TableCell>
                <TableCell align="right">Débit</TableCell>
                <TableCell align="right">Crédit</TableCell>
                <TableCell align="right">Solde</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {comptes.map((compte) => {
                // Simuler les valeurs débit/crédit (dans une vraie application, ces valeurs viendraient de l'API)
                let debit = 0;
                let credit = 0;

                if (compte.type === 'actif' || compte.type === 'charge') {
                  debit = parseFloat(compte.solde);
                } else {
                  credit = parseFloat(compte.solde);
                }

                return (
                  <TableRow key={compte.id}>
                    <TableCell>{compte.code}</TableCell>
                    <TableCell>{compte.nom}</TableCell>
                    <TableCell>
                      {compte.type.charAt(0).toUpperCase() + compte.type.slice(1)}
                    </TableCell>
                    <TableCell align="right">{debit.toFixed(2)} €</TableCell>
                    <TableCell align="right">{credit.toFixed(2)} €</TableCell>
                    <TableCell align="right">{parseFloat(compte.solde).toFixed(2)} €</TableCell>
                  </TableRow>
                );
              })}

              {/* Ligne de total */}
              <TableRow sx={{ bgcolor: 'grey.100', fontWeight: 'bold' }}>
                <TableCell colSpan={3}>
                  <Typography variant="subtitle1" fontWeight="bold">
                    Total
                  </Typography>
                </TableCell>
                <TableCell align="right">
                  <Typography variant="subtitle1" fontWeight="bold">
                    {totals.debit.toFixed(2)} €
                  </Typography>
                </TableCell>
                <TableCell align="right">
                  <Typography variant="subtitle1" fontWeight="bold">
                    {totals.credit.toFixed(2)} €
                  </Typography>
                </TableCell>
                <TableCell align="right">
                  <Typography variant="subtitle1" fontWeight="bold">
                    {totals.solde.toFixed(2)} €
                  </Typography>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </TableContainer>
      </Paper>
    </Box>
  );
};

export default BalanceExport;
