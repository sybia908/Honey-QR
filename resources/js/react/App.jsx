import React, { useState, useEffect } from 'react';
import Dashboard from './components/Dashboard';
import Header from './components/Header';

function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Fetch user data
    fetch('/api/profile')
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          setUser(data.user);
        }
        setLoading(false);
      })
      .catch(error => {
        console.error('Error fetching user data:', error);
        setLoading(false);
      });
  }, []);

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  if (loading) {
    return (
      <div className="loading-container">
        <div className="loading-spinner"></div>
        <p>Memuat...</p>
      </div>
    );
  }

  return (
    <div className="app-container">
      <div className="main-content no-sidebar">
        <Header toggleMenu={toggleMenu} menuOpen={menuOpen} user={user} />
        <Dashboard user={user} />
      </div>
    </div>
  );
}

export default App;
