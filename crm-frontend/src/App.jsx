import { Routes, Route, Navigate, Link, useNavigate } from 'react-router-dom';
import Contacts from './pages/Contacts'; // We'll create these placeholder view files next
import Dashboard from './pages/Dashboard';

// 1. Simple Guard Component to intercept unauthenticated requests
function ProtectedRoute({ children }) {
  const token = localStorage.getItem('token');
  if (!token) {
    return <Navigate to="/login" replace />;
  }
  return children;
}

// 2. Mock Login Page Component for direct interface wireframes
function Login() {
  const navigate = useNavigate();
  
  const handleMockLogin = () => {
    // In production, this token is returned from your Postman-tested /api/login endpoint
    localStorage.setItem('token', 'mock_sanctum_token');
    navigate('/dashboard');
  };

  return (
    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh', fontFamily: 'sans-serif' }}>
      <div style={{ padding: '2rem', border: '1px solid #ccc', borderRadius: '8px', textAlign: 'center' }}>
        <h2>CRM Secure Portal</h2>
        <button onClick={handleMockLogin} style={{ padding: '0.5rem 1rem', background: '#4f46e5', color: '#fff', border: '0', borderRadius: '4px', cursor: 'pointer' }}>
          Simulate Token Authenticated Sign In
        </button>
      </div>
    </div>
  );
}

// 3. Main Application Base Frame Layout
export default function App() {
  return (
    <Routes>
      {/* Public Route Gateway */}
      <Route path="/login" element={<Login />} />

      {/* Protected CRM Framework Dashboard Shell Layer */}
      <Route 
        path="/*" 
        element={
          <ProtectedRoute>
            <div style={{ fontFamily: 'sans-serif', minHeight: '100vh', background: '#f8fafc' }}>
              {/* Dynamic Global Top Navigation Navbar Strip */}
              <nav style={{ background: '#fff', borderBottom: '1px solid #e2e8f0', padding: '1rem flex', display: 'flex', gap: '2rem', justifyContent: 'center', alignItems: 'center', height: '50px' }}>
                <Link to="/dashboard" style={{ textDecoration: 'none', color: '#4f46e5', fontWeight: 'bold' }}>Dashboard</Link>
                <Link to="/contacts" style={{ textDecoration: 'none', color: '#64748b', fontWeight: 'bold' }}>Contacts Matrix</Link>
                <button 
                  onClick={() => { localStorage.clear(); window.location.href = '/login'; }}
                  style={{ background: 'none', border: 'none', color: '#ef4444', cursor: 'pointer', fontWeight: 'bold' }}
                >
                  Logout
                </button>
              </nav>

              {/* Central Dynamic Screen Render Target Area */}
              <main style={{ padding: '2rem', maxWidth: '1200px', margin: '0 auto' }}>
                <Routes>
                  <Route path="/dashboard" element={<Dashboard />} />
                  <Route path="/contacts" element={<Contacts />} />
                  <Route path="*" element={<Navigate to="/dashboard" replace />} />
                </Routes>
              </main>
            </div>
          </ProtectedRoute>
        } 
      />
    </Routes>
  );
}