# Panduan Deployment Honey QR

Dokumen ini berisi panduan lengkap untuk men-deploy aplikasi Honey QR ke GitHub Pages dan menggunakan Supabase sebagai database.

## 1. Persiapan Repository GitHub

1. Buat repository baru di GitHub dengan nama `Honey-QR`
2. Inisialisasi Git di direktori lokal proyek (jika belum):
   ```bash
   git init
   ```
3. Tambahkan remote repository:
   ```bash
   git remote add origin https://github.com/sybia908/Honey-QR.git
   ```

## 2. Konfigurasi Supabase

1. Login ke [Supabase](https://supabase.com) menggunakan akun Anda
2. Buka project yang sudah dibuat (URL: https://dhpogmrulnvmkhdzjpqh.supabase.co)
3. Buat tabel-tabel yang diperlukan menggunakan SQL Editor:

```sql
-- Tabel users
CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'siswa',
    is_active BOOLEAN NOT NULL DEFAULT true,
    remember_token VARCHAR(100),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel teachers
CREATE TABLE IF NOT EXISTS public.teachers (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    nip VARCHAR(50) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel classes
CREATE TABLE IF NOT EXISTS public.classes (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    level VARCHAR(20) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel students
CREATE TABLE IF NOT EXISTS public.students (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    class_id BIGINT REFERENCES public.classes(id) ON DELETE SET NULL,
    nis VARCHAR(50) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel subjects
CREATE TABLE IF NOT EXISTS public.subjects (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel qrcodes
CREATE TABLE IF NOT EXISTS public.qrcodes (
    id BIGSERIAL PRIMARY KEY,
    teacher_id BIGINT NOT NULL REFERENCES public.teachers(id) ON DELETE CASCADE,
    subject_id BIGINT REFERENCES public.subjects(id) ON DELETE SET NULL,
    class_id BIGINT REFERENCES public.classes(id) ON DELETE SET NULL,
    code VARCHAR(100) NOT NULL UNIQUE,
    valid_until TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Tabel attendances
CREATE TABLE IF NOT EXISTS public.attendances (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT NOT NULL REFERENCES public.students(id) ON DELETE CASCADE,
    qrcode_id BIGINT NOT NULL REFERENCES public.qrcodes(id) ON DELETE CASCADE,
    status VARCHAR(20) NOT NULL DEFAULT 'hadir',
    scanned_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
```

4. Buat bucket storage untuk menyimpan file:
   - Buka tab "Storage" di dashboard Supabase
   - Klik "Create a new bucket"
   - Beri nama "public" dan centang "Public bucket"
   - Klik "Create bucket"

## 3. Konfigurasi Aplikasi untuk Supabase

1. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

2. Edit file `.env` dan sesuaikan dengan konfigurasi Supabase:
   ```
   APP_NAME="Honey QR"
   APP_ENV=production
   APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   APP_DEBUG=false
   APP_URL=https://sybia908.github.io/Honey-QR

   DB_CONNECTION=pgsql
   DB_HOST=db.dhpogmrulnvmkhdzjpqh.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your_supabase_db_password

   SUPABASE_URL=https://dhpogmrulnvmkhdzjpqh.supabase.co
   SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac
   ```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

## 4. Persiapan Deployment

1. Pastikan semua dependensi terinstal:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   ```

2. Compile asset untuk production:
   ```bash
   npm run build
   ```

3. Optimalkan Laravel untuk production:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 5. Deployment ke GitHub

1. Tambahkan semua file ke Git:
   ```bash
   git add .
   ```

2. Commit perubahan:
   ```bash
   git commit -m "Initial commit for Honey QR"
   ```

3. Push ke GitHub:
   ```bash
   git push -u origin main
   ```

4. GitHub Actions akan otomatis men-deploy aplikasi ke GitHub Pages setelah push ke branch main.

## 6. Konfigurasi GitHub Pages

1. Buka repository di GitHub
2. Buka tab "Settings"
3. Klik "Pages" di sidebar
4. Di bagian "Source", pilih "GitHub Actions"
5. Tunggu hingga deployment selesai

## 7. Verifikasi Deployment

1. Buka URL GitHub Pages: https://sybia908.github.io/Honey-QR
2. Pastikan aplikasi berjalan dengan baik
3. Periksa koneksi ke Supabase dengan membuka halaman contoh Supabase: https://sybia908.github.io/Honey-QR/supabase/example

## Troubleshooting

### Masalah Koneksi Database

Jika terjadi masalah koneksi ke database Supabase:

1. Periksa kredensial database di file `.env`
2. Pastikan IP address server tidak diblokir oleh Supabase
3. Periksa log error di storage/logs/laravel.log

### Masalah Deployment

Jika GitHub Actions gagal men-deploy:

1. Periksa tab "Actions" di repository GitHub untuk melihat log error
2. Pastikan file `.github/workflows/deploy.yml` sudah benar
3. Periksa apakah repository memiliki secret yang diperlukan

## Catatan Penting

- Jangan pernah meng-commit file `.env` ke repository Git
- Selalu gunakan HTTPS untuk mengakses API Supabase
- Perbarui password database secara berkala
- Backup database Supabase secara teratur
