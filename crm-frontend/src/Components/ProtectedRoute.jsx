// src/components/ProtectedRoute.jsx
import { Navigate, Outlet } from 'react-router-dom';

export default function ProtectedRoute() {
  const token = localStorage.getItem('token');
  
  // If no token exists, bounce them straight back to login page
  if (!token) {
    return <Navigate to="/login" replace />;
  }
  
  // Otherwise, render the child pages safely
  return <Outlet />;
}