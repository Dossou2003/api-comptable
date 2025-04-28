import React from 'react';
import { Box, Typography, Button, Breadcrumbs, Link as MuiLink } from '@mui/material';
import { Link } from 'react-router-dom';
import { NavigateNext } from '@mui/icons-material';

const PageHeader = ({ 
  title, 
  actionText, 
  actionIcon, 
  actionLink, 
  showAction = true,
  breadcrumbs = []
}) => {
  return (
    <Box sx={{ mb: 3 }}>
      {breadcrumbs.length > 0 && (
        <Breadcrumbs 
          separator={<NavigateNext fontSize="small" />} 
          aria-label="breadcrumb"
          sx={{ mb: 1 }}
        >
          <MuiLink component={Link} to="/" color="inherit">
            Accueil
          </MuiLink>
          
          {breadcrumbs.map((crumb, index) => (
            <MuiLink 
              key={index}
              component={Link} 
              to={crumb.link} 
              color={index === breadcrumbs.length - 1 ? 'text.primary' : 'inherit'}
              underline={index === breadcrumbs.length - 1 ? 'none' : 'hover'}
            >
              {crumb.label}
            </MuiLink>
          ))}
        </Breadcrumbs>
      )}
      
      <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <Typography variant="h4" component="h1">
          {title}
        </Typography>
        
        {showAction && actionText && (
          <Button 
            component={Link} 
            to={actionLink} 
            variant="contained" 
            startIcon={actionIcon}
          >
            {actionText}
          </Button>
        )}
      </Box>
    </Box>
  );
};

export default PageHeader;
