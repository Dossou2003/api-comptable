import React, { useState } from 'react';
import { Outlet } from 'react-router-dom';
import { Box, Toolbar, Container, IconButton, useMediaQuery, useTheme } from '@mui/material';
import { Menu as MenuIcon } from '@mui/icons-material';
import Navbar from './Navbar';
import Sidebar from './Sidebar';
import '../../styles/layout.css';

const Dashboard = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const theme = useTheme();
  const isMobile = useMediaQuery(theme.breakpoints.down('md'));

  const toggleSidebar = () => {
    setSidebarOpen(!sidebarOpen);
  };

  return (
    <div className="app-container">
      <Navbar>
        {isMobile && (
          <IconButton
            color="inherit"
            aria-label="open drawer"
            edge="start"
            onClick={toggleSidebar}
            sx={{ mr: 2 }}
          >
            <MenuIcon />
          </IconButton>
        )}
      </Navbar>

      <div className={`sidebar ${sidebarOpen ? 'open' : ''}`}>
        <Sidebar />
      </div>

      <div className="main-content content-with-navbar">
        <Container maxWidth="lg" sx={{ py: 4 }}>
          <Outlet />
        </Container>
      </div>
    </div>
  );
};

export default Dashboard;
