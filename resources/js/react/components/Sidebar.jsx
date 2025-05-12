import React from 'react';

function Sidebar({ isOpen, user }) {
  const userRole = user?.roles?.[0]?.name || 'guest';

  return (
    <div className={`sidebar ${isOpen ? 'open' : 'closed'}`}>
      <div className="sidebar-header">
        <h2 className="app-name">Aplikasi Presensi</h2>
      </div>
      
      <div className="user-info">
        <div className="user-avatar">
          <img src={user?.avatar || 'https://via.placeholder.com/64'} alt="User" />
        </div>
        <div className="user-details">
          <h3>{user?.name || 'Tamu'}</h3>
          <p>{userRole.charAt(0).toUpperCase() + userRole.slice(1)}</p>
        </div>
      </div>
      
      <nav className="sidebar-nav">
        <a href="/dashboard" className="nav-item active">
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
      
      <div className="sidebar-footer">
        <form action="/logout" method="POST">
          <button type="submit" className="logout-btn">
            <i className="bi bi-box-arrow-right"></i>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </div>
  );
}

export default Sidebar;
