# Panduan Deployment ke Render.com

Berikut adalah panduan lengkap untuk men-deploy aplikasi Honey QR ke Render.com:

## 1. Persiapan File Konfigurasi

Kami telah membuat file konfigurasi `render.yaml` yang diperlukan untuk deployment ke Render.com. File ini berisi:
- Konfigurasi build dan start command
- Environment variables
- Health check path

## 2. Langkah-langkah Deployment ke Render.com

### Langkah 1: Buat Akun Render

1. Buka [render.com](https://render.com) dan daftar/login
2. Anda bisa mendaftar dengan akun GitHub untuk mempermudah proses

### Langkah 2: Hubungkan Repository GitHub

1. Di dashboard Render, klik **New** dan pilih **Web Service**
2. Pilih **Connect a repository**
3. Pilih repository GitHub **Honey-QR**
4. Klik **Connect**

### Langkah 3: Konfigurasi Web Service

1. Pada halaman konfigurasi:
   - **Name**: `honey-qr` (atau nama yang Anda inginkan)
   - **Environment**: `PHP`
   - **Region**: Pilih region terdekat dengan pengguna aplikasi
   - **Branch**: `main`

2. Di bagian **Build & Deploy**:
   - **Build Command**: `composer install --no-interaction --prefer-dist --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache`
   - **Start Command**: `php artisan serve --host 0.0.0.0 --port $PORT`

3. Tambahkan Environment Variables:
   - `APP_NAME`: `Honey QR`
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `APP_KEY`: Biarkan kosong (akan di-generate otomatis)
   - `DB_CONNECTION`: `pgsql`
   - `DB_HOST`: `db.dhpogmrulnvmkhdzjpqh.supabase.co`
   - `DB_PORT`: `5432`
   - `DB_DATABASE`: `postgres`
   - `DB_USERNAME`: `postgres`
   - `DB_PASSWORD`: `G4l4xymini`
   - `SUPABASE_URL`: `https://dhpogmrulnvmkhdzjpqh.supabase.co`
   - `SUPABASE_KEY`: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac`
   - `LOG_CHANNEL`: `stderr`
   - `CACHE_DRIVER`: `file`
   - `SESSION_DRIVER`: `cookie`
   - `SESSION_LIFETIME`: `120`

4. Klik **Create Web Service**

### Langkah 4: Tunggu Proses Deployment

1. Render akan memulai proses build dan deployment
2. Proses ini biasanya membutuhkan waktu 5-10 menit untuk selesai
3. Anda bisa melihat log build dan deployment secara real-time

### Langkah 5: Verifikasi Deployment

1. Setelah deployment selesai, Render akan menampilkan URL aplikasi Anda
2. Klik URL tersebut untuk membuka aplikasi
3. Pastikan semua fitur berfungsi dengan baik

## 3. Troubleshooting

### Masalah: Build Gagal

Jika proses build gagal, periksa log untuk melihat error yang terjadi. Beberapa masalah umum:

1. **Composer Dependencies**: Pastikan semua dependencies di composer.json valid
2. **Node.js Dependencies**: Pastikan semua dependencies di package.json valid
3. **PHP Version**: Pastikan versi PHP yang digunakan kompatibel dengan aplikasi

### Masalah: Aplikasi Error Setelah Deployment

1. Periksa log aplikasi di dashboard Render
2. Pastikan environment variables sudah benar
3. Pastikan database Supabase dapat diakses dari Render

## 4. Menggunakan Custom Domain (Opsional)

1. Di dashboard Render, pilih web service Anda
2. Klik tab **Settings**
3. Scroll ke bawah ke bagian **Custom Domain**
4. Klik **Add Custom Domain**
5. Ikuti petunjuk untuk mengatur DNS

## 5. Pemeliharaan dan Update

1. Setiap push ke branch main di GitHub akan memicu deployment otomatis
2. Anda bisa menonaktifkan auto-deploy di pengaturan web service
3. Untuk update manual, klik tombol **Manual Deploy** di dashboard Render

## 6. Menggunakan render.yaml (Alternatif)

Jika Anda ingin menggunakan file `render.yaml` yang telah kami buat:

1. Di dashboard Render, klik **New** dan pilih **Blueprint**
2. Pilih repository GitHub yang berisi file `render.yaml`
3. Render akan otomatis mengonfigurasi web service berdasarkan file tersebut

## 7. Monitoring

Render menyediakan fitur monitoring dasar untuk web service Anda:

1. Di dashboard web service, klik tab **Metrics**
2. Anda bisa melihat CPU usage, memory usage, dan response time
3. Untuk monitoring lebih lanjut, pertimbangkan untuk mengintegrasikan dengan layanan monitoring pihak ketiga
