import React from 'react';
import { Card, CardContent, Typography, Box, IconButton } from '@mui/material';
import { ArrowForward } from '@mui/icons-material';
import { Link } from 'react-router-dom';

const StatCard = ({ 
  title, 
  value, 
  icon: Icon, 
  color = 'primary', 
  link,
  subtitle,
  trend,
  trendValue
}) => {
  return (
    <Card 
      sx={{ 
        height: '100%',
        position: 'relative',
        overflow: 'visible',
        '&:before': {
          content: '""',
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '5px',
          backgroundColor: `${color}.main`,
          borderTopLeftRadius: '4px',
          borderTopRightRadius: '4px',
        }
      }}
    >
      <CardContent>
        <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <Box>
            <Typography variant="subtitle2" color="text.secondary" gutterBottom>
              {title}
            </Typography>
            
            <Typography variant="h4" component="div" fontWeight="bold">
              {value}
            </Typography>
            
            {subtitle && (
              <Typography variant="body2" color="text.secondary" sx={{ mt: 1 }}>
                {subtitle}
              </Typography>
            )}
            
            {trend && (
              <Box sx={{ display: 'flex', alignItems: 'center', mt: 1 }}>
                <Typography 
                  variant="body2" 
                  color={trendValue > 0 ? 'success.main' : trendValue < 0 ? 'error.main' : 'text.secondary'}
                  sx={{ display: 'flex', alignItems: 'center' }}
                >
                  {trend}
                </Typography>
              </Box>
            )}
          </Box>
          
          <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
            <Box 
              sx={{ 
                p: 1.5, 
                borderRadius: '50%', 
                backgroundColor: `${color}.light`,
                color: `${color}.main`,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center'
              }}
            >
              <Icon fontSize="medium" />
            </Box>
            
            {link && (
              <IconButton 
                component={Link} 
                to={link}
                size="small"
                sx={{ mt: 1 }}
                color={color}
              >
                <ArrowForward fontSize="small" />
              </IconButton>
            )}
          </Box>
        </Box>
      </CardContent>
    </Card>
  );
};

export default StatCard;
