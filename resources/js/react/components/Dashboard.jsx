import React, { useState, useEffect } from 'react';

function Dashboard({ user }) {
  const [stats, setStats] = useState({
    totalClasses: 0,
    totalStudents: 0,
    totalTeachers: 0,
    totalAttendances: 0
  });
  
  const [attendances, setAttendances] = useState([]);
  const [qrcodes, setQrCodes] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Fungsi untuk memuat data dari API
    const fetchDashboardData = async () => {
      try {
        // Dalam aplikasi nyata, ini akan mengambil data dari endpoint API
        // Contoh data dummy untuk tampilan
        setTimeout(() => {
          setStats({
            totalClasses: 12,
            totalStudents: 50,
            totalTeachers: 10,
            totalAttendances: 25
          });
          
          setAttendances([
            { id: 1, date: '2025-05-11', user: 'Ahmad Fadli', className: 'X IPA 1', status: 'on_time' },
            { id: 2, date: '2025-05-11', user: 'Siti Rahayu', className: 'XI IPS 2', status: 'late' },
            { id: 3, date: '2025-05-11', user: 'Budi Santoso', className: 'XII Bahasa 1', status: 'on_time' },
            { id: 4, date: '2025-05-10', user: 'Dian Permata', className: 'X IPA 3', status: 'on_time' },
            { id: 5, date: '2025-05-10', user: 'Eko Prasetyo', className: 'XI IPA 1', status: 'late' }
          ]);
          
          setQrCodes([
            { id: 1, code: 'QR-AF842BX1', validUntil: '2025-05-18', creator: 'Pak Agus' },
            { id: 2, code: 'QR-97ZXKP23', validUntil: '2025-05-15', creator: 'Bu Sinta' },
            { id: 3, code: 'QR-L32VB54M', validUntil: '2025-05-17', creator: 'Pak Doni' }
          ]);
          
          setLoading(false);
        }, 1000);
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
        setLoading(false);
      }
    };

    fetchDashboardData();
  }, []);

  if (loading) {
    return (
      <div className="dashboard-loading">
        <div className="loading-spinner"></div>
        <p>Mengambil data dashboard...</p>
      </div>
    );
  }

  return (
    <div className="dashboard">
      <div className="dashboard-header">
        <h2>Dashboard</h2>
        <p className="welcome-message">Selamat datang kembali, {user?.name || 'Pengguna'}!</p>
      </div>
      
      <div className="stats-cards">
        <div className="stat-card color-1">
          <div className="stat-icon">
            <i className="bi bi-building"></i>
          </div>
          <div className="stat-content">
            <h3>Kelas</h3>
            <p className="stat-value">{stats.totalClasses}</p>
          </div>
        </div>
        
        <div className="stat-card color-2">
          <div className="stat-icon">
            <i className="bi bi-mortarboard"></i>
          </div>
          <div className="stat-content">
            <h3>Siswa</h3>
            <p className="stat-value">{stats.totalStudents}</p>
          </div>
        </div>
        
        <div className="stat-card color-3">
          <div className="stat-icon">
            <i className="bi bi-person-workspace"></i>
          </div>
          <div className="stat-content">
            <h3>Guru</h3>
            <p className="stat-value">{stats.totalTeachers}</p>
          </div>
        </div>
        
        <div className="stat-card color-4">
          <div className="stat-icon">
            <i className="bi bi-calendar-check"></i>
          </div>
          <div className="stat-content">
            <h3>Absensi Hari Ini</h3>
            <p className="stat-value">{stats.totalAttendances}</p>
          </div>
        </div>
      </div>
      
      <div className="dashboard-content">
        <div className="dashboard-card attendance-table">
          <div className="card-header">
            <h3>Absensi Terbaru</h3>
          </div>
          <div className="card-body">
            <table>
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Nama</th>
                  <th>Kelas</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                {attendances.length > 0 ? (
                  attendances.map(attendance => (
                    <tr key={attendance.id}>
                      <td>{attendance.date}</td>
                      <td>{attendance.user}</td>
                      <td>{attendance.className}</td>
                      <td>
                        <span className={`badge ${attendance.status === 'on_time' ? 'badge-success' : 'badge-warning'}`}>
                          {attendance.status === 'on_time' ? 'Tepat Waktu' : 'Terlambat'}
                        </span>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="4" className="empty-data">Belum ada data absensi</td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
          <div className="card-footer">
            <a href="/attendances" className="btn btn-primary">Lihat Semua</a>
          </div>
        </div>
        
        <div className="dashboard-card qrcode-list">
          <div className="card-header">
            <h3>QR Code Aktif</h3>
          </div>
          <div className="card-body">
            <ul className="qrcode-items">
              {qrcodes.length > 0 ? (
                qrcodes.map(qrcode => (
                  <li key={qrcode.id} className="qrcode-item">
                    <div className="qrcode-code">{qrcode.code}</div>
                    <div className="qrcode-details">
                      <span>Valid sampai: {qrcode.validUntil}</span>
                      <span>Dibuat oleh: {qrcode.creator}</span>
                    </div>
                  </li>
                ))
              ) : (
                <li className="empty-data">Belum ada QR Code aktif</li>
              )}
            </ul>
          </div>
          <div className="card-footer">
            <a href="/qrcodes" className="btn btn-primary">Lihat Semua</a>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Dashboard;
