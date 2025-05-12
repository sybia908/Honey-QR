# Panduan Deployment ke cPanel

Berikut adalah panduan lengkap untuk men-deploy aplikasi Honey QR ke hosting cPanel:

## 1. Persiapan File

Kami telah membuat file `.htaccess` di root direktori untuk mengalihkan semua request ke folder `public`. File ini penting untuk memastikan aplikasi Laravel berjalan dengan benar di cPanel.

## 2. Langkah-langkah Deployment ke cPanel

### Langkah 1: Login ke cPanel

1. Buka URL cPanel yang disediakan oleh penyedia hosting Anda
2. Login dengan username dan password yang diberikan

### Langkah 2: Siapkan Database

1. Di dashboard cPanel, cari dan klik **MySQL Databases**
2. Buat database baru:
   - Masukkan nama database (misalnya `honeyqr_db`)
   - Klik **Create Database**
3. Buat user database baru:
   - Masukkan nama user (misalnya `honeyqr_user`)
   - Masukkan password yang kuat
   - Klik **Create User**
4. Tambahkan user ke database:
   - Pilih database dan user yang baru dibuat
   - Berikan semua hak akses (ALL PRIVILEGES)
   - Klik **Add**

### Langkah 3: Upload File Aplikasi

#### Metode 1: Menggunakan File Manager

1. Di dashboard cPanel, klik **File Manager**
2. Navigasi ke direktori `public_html` atau subdomain yang ingin digunakan
3. Klik **Upload** dan upload file aplikasi Anda (dalam bentuk ZIP)
4. Extract file ZIP tersebut

#### Metode 2: Menggunakan FTP

1. Gunakan FTP client seperti FileZilla
2. Hubungkan ke server dengan kredensial FTP Anda
3. Upload semua file aplikasi ke direktori `public_html` atau subdomain

### Langkah 4: Konfigurasi Aplikasi

1. Buat atau edit file `.env` di root direktori aplikasi:
   ```
   APP_NAME="Honey QR"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=honeyqr_db
   DB_USERNAME=honeyqr_user
   DB_PASSWORD=your_password
   
   SUPABASE_URL=https://dhpogmrulnvmkhdzjpqh.supabase.co
   SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac
   ```

2. Atur permission file dan direktori:
   - Direktori `storage` dan `bootstrap/cache`: 755
   - File di dalam direktori `storage`: 644
   - Untuk mengatur permission, gunakan File Manager cPanel atau perintah FTP

### Langkah 5: Instalasi Aplikasi

1. Akses terminal SSH (jika tersedia di cPanel):
   - Klik **Terminal** di dashboard cPanel
   - Atau gunakan SSH client seperti PuTTY

2. Jalankan perintah berikut:
   ```bash
   cd /path/to/your/application
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Jika SSH tidak tersedia, Anda perlu menjalankan perintah ini di lokal dan upload hasilnya:
   - Jalankan `composer install --no-dev --optimize-autoloader` di lokal
   - Generate key dengan `php artisan key:generate`
   - Tambahkan key yang dihasilkan ke file `.env`
   - Upload semua file ke server

### Langkah 6: Konfigurasi Supabase

1. Pastikan aplikasi Anda menggunakan kredensial Supabase yang benar:
   - URL: https://dhpogmrulnvmkhdzjpqh.supabase.co
   - API Key: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac
   - Password Database: G4l4xymini

2. Pastikan bucket storage `honeyqr-storage` sudah dibuat di Supabase

## 3. Troubleshooting

### Masalah: 500 Internal Server Error

Jika Anda mendapatkan error 500, periksa:
1. File `.htaccess` di root dan di folder `public`
2. Permission file dan direktori
3. File log di `storage/logs/laravel.log`

### Masalah: Koneksi Database

Jika aplikasi tidak dapat terhubung ke database:
1. Periksa kredensial database di file `.env`
2. Pastikan user database memiliki hak akses yang cukup
3. Coba buat koneksi manual ke database untuk memastikan kredensial benar

### Masalah: Koneksi Supabase

Jika aplikasi tidak dapat terhubung ke Supabase:
1. Periksa kredensial Supabase di file `.env`
2. Pastikan IP server cPanel tidak diblokir oleh Supabase

## 4. Pemeliharaan

### Update Aplikasi

Untuk memperbarui aplikasi:
1. Backup semua file dan database
2. Upload file baru ke server
3. Jalankan perintah update (jika diperlukan):
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Backup Reguler

Lakukan backup reguler:
1. Backup database melalui phpMyAdmin di cPanel
2. Backup file aplikasi melalui File Manager atau FTP
3. Simpan backup di tempat yang aman
