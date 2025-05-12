import React, { useState, useEffect, useRef } from 'react';

function Header({ toggleMenu, menuOpen, user }) {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const dropdownRef = useRef(null);
  const userRole = user?.roles?.[0]?.name || 'guest';
  
  // Menutup dropdown ketika klik di luar
  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setDropdownOpen(false);
      }
    }
    
    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);
  
  return (
    <header className="app-header honey-theme">
      <div className="header-brand">
        <button className="menu-toggle" onClick={toggleMenu}>
          <i className="bi bi-list"></i>
        </button>
        
        <div className="header-title">
          <h1>Aplikasi Presensi</h1>
        </div>
      </div>
      
      <nav className={`header-nav ${menuOpen ? 'open' : ''}`}>
        <a href="/dashboard" className="nav-item">
          <i className="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </a>
        
        {(userRole === 'admin' || userRole === 'guru') && (
          <>
            <a href="/users" className="nav-item">
              <i className="bi bi-people"></i>
              <span>Pengguna</span>
            </a>
            <a href="/teachers" className="nav-item">
              <i className="bi bi-person-badge"></i>
              <span>Guru</span>
            </a>
            <a href="/classes" className="nav-item">
              <i className="bi bi-building"></i>
              <span>Kelas</span>
            </a>
            <a href="/students" className="nav-item">
              <i className="bi bi-mortarboard"></i>
              <span>Siswa</span>
            </a>
            <a href="/subjects" className="nav-item">
              <i className="bi bi-book"></i>
              <span>Mata Pelajaran</span>
            </a>
            <a href="/qrcodes" className="nav-item">
              <i className="bi bi-qr-code"></i>
              <span>QR Code</span>
            </a>
          </>
        )}
        
        {userRole === 'siswa' && (
          <a href="/scan" className="nav-item">
            <i className="bi bi-qr-code-scan"></i>
            <span>Scan QR</span>
          </a>
        )}
        
        <a href="/attendances" className="nav-item">
          <i className="bi bi-calendar-check"></i>
          <span>Absensi</span>
        </a>
      </nav>
      
      <div className="header-actions" ref={dropdownRef}>
        <div className="user-dropdown">
          <button 
            className="profile-button" 
            onClick={() => setDropdownOpen(!dropdownOpen)}
          >
            <img src={user?.avatar || 'https://via.placeholder.com/36'} alt="Profile" />
            <span className="user-name">{user?.name || 'Tamu'}</span>
            <span className="user-role">{userRole.charAt(0).toUpperCase() + userRole.slice(1)}</span>
            <i className="bi bi-chevron-down"></i>
          </button>
          
          {dropdownOpen && (
            <div className="dropdown-menu">
              <a href="/profile" className="dropdown-item">
                <i className="bi bi-person"></i>
                <span>Profil</span>
              </a>
              <a href="/settings" className="dropdown-item">
                <i className="bi bi-gear"></i>
                <span>Pengaturan</span>
              </a>
              <div className="dropdown-divider"></div>
              <form action="/logout" method="POST">
                <button type="submit" className="dropdown-item logout-item">
                  <i className="bi bi-box-arrow-right"></i>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          )}
        </div>
      </div>
    </header>
  );
}

export default Header;
