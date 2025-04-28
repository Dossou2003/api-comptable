import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';

// Composants d'authentification
import Login from './components/auth/Login';
import PrivateRoute from './components/auth/PrivateRoute';

// Composants de mise en page
import Dashboard from './components/layout/Dashboard';
import DashboardHome from './components/dashboard/DashboardHome';

// Composants pour les comptes
import CompteList from './components/comptes/CompteList';
import CompteForm from './components/comptes/CompteForm';
import CompteDetail from './components/comptes/CompteDetail';

// Composants pour les transactions
import TransactionList from './components/transactions/TransactionList';
import TransactionForm from './components/transactions/TransactionForm';
import TransactionDetail from './components/transactions/TransactionDetail';

// Composants pour le journal et la balance
import JournalList from './components/journal/JournalList';
import BalanceExport from './components/balance/BalanceExport';

const theme = createTheme({
  palette: {
    primary: {
      main: '#1976d2',
    },
    secondary: {
      main: '#dc004e',
    },
  },
});

function App() {
  return (
    <ThemeProvider theme={theme}>
      <CssBaseline />
      <Router>
        <Routes>
          <Route path="/login" element={<Login />} />

          <Route path="/" element={<PrivateRoute><Dashboard /></PrivateRoute>}>
            <Route index element={<Navigate to="/dashboard" replace />} />
            <Route path="dashboard" element={<DashboardHome />} />

            <Route path="comptes">
              <Route index element={<CompteList />} />
              <Route path="new" element={<CompteForm />} />
              <Route path=":id" element={<CompteDetail />} />
              <Route path=":id/edit" element={<CompteForm />} />
            </Route>

            <Route path="transactions">
              <Route index element={<TransactionList />} />
              <Route path="new" element={<TransactionForm />} />
              <Route path=":id" element={<TransactionDetail />} />
            </Route>

            <Route path="journal" element={<JournalList />} />
            <Route path="balance" element={<BalanceExport />} />
          </Route>

          <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Router>
    </ThemeProvider>
  );
}

export default App;
