# Panduan Deployment ke Vercel

Berikut adalah panduan lengkap untuk men-deploy aplikasi Honey QR ke Vercel:

## 1. Persiapan File Konfigurasi Vercel

Kami telah membuat file konfigurasi yang diperlukan untuk deployment ke Vercel:

1. **vercel.json** - Konfigurasi utama untuk Vercel
2. **api/index.php** - File entrypoint untuk Vercel

## 2. Langkah-langkah Deployment ke Vercel

### Langkah 1: Login ke Vercel

1. Buka [vercel.com](https://vercel.com) dan login dengan akun Anda
2. Jika belum memiliki akun, Anda bisa mendaftar dengan akun GitHub

### Langkah 2: Import Project

1. Klik tombol **Add New** > **Project**
2. Pilih tab **Import Git Repository**
3. Pilih repository GitHub **Honey-QR**
4. Klik **Import**

### Langkah 3: Konfigurasi Project

1. Pada halaman konfigurasi project:
   - **Framework Preset**: Pilih **Other**
   - **Root Directory**: Biarkan default (/)
   - **Build Command**: `npm run build`
   - **Output Directory**: `public`

2. Klik **Environment Variables** dan tambahkan:
   - `DB_PASSWORD`: `G4l4xymini`
   - `SUPABASE_KEY`: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac`

3. Klik **Deploy**

### Langkah 4: Tunggu Proses Deployment

1. Vercel akan memulai proses build dan deployment
2. Tunggu hingga proses selesai (biasanya membutuhkan waktu beberapa menit)

### Langkah 5: Verifikasi Deployment

1. Setelah deployment selesai, Vercel akan menampilkan URL aplikasi Anda
2. Klik URL tersebut untuk membuka aplikasi
3. Pastikan semua fitur berfungsi dengan baik

## 3. Troubleshooting

### Masalah: No Output Directory named "dist" found

Jika Anda mendapatkan error "No Output Directory named 'dist' found after the Build completed", pastikan:

1. File `vercel.json` sudah benar dan berada di root project
2. Direktori `api` dengan file `index.php` sudah dibuat
3. Pada konfigurasi project di Vercel, pastikan **Output Directory** diatur ke `public`

### Masalah: Koneksi Database

Jika aplikasi tidak dapat terhubung ke database Supabase:

1. Periksa environment variables di Vercel
2. Pastikan `DB_PASSWORD` dan `SUPABASE_KEY` sudah benar
3. Periksa apakah IP Vercel diizinkan di konfigurasi Supabase

## 4. Custom Domain (Opsional)

Jika Anda ingin menggunakan domain kustom:

1. Di dashboard Vercel, pilih project Anda
2. Klik tab **Domains**
3. Klik **Add**
4. Masukkan domain Anda dan ikuti petunjuk untuk mengatur DNS

## 5. Pemeliharaan

Setiap kali Anda melakukan push ke repository GitHub, Vercel akan otomatis men-deploy perubahan tersebut. Tidak perlu melakukan deployment manual lagi.
