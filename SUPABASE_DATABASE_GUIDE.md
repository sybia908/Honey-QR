# Panduan Pembuatan Database di Supabase

Berikut adalah langkah-langkah detail untuk membuat dan mengkonfigurasi database di Supabase untuk aplikasi Honey QR:

## 1. Akses SQL Editor di Supabase

1. Login ke dashboard Supabase: [app.supabase.com](https://app.supabase.com)
2. Pilih project dengan URL: `db.dhpogmrulnvmkhdzjpqh.supabase.co`
3. Di menu sebelah kiri, klik **SQL Editor**
4. Klik **+ New query** untuk membuat query baru

## 2. Membuat Tabel dengan SQL

Salin dan tempel SQL berikut ke dalam editor:

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

-- Insert sample admin user
INSERT INTO public.users (name, email, password, role)
VALUES ('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample teacher
INSERT INTO public.users (name, email, password, role)
VALUES ('Budi Santoso', 'budi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru');

-- Get the teacher user ID
DO $$ 
DECLARE
    teacher_user_id BIGINT;
BEGIN
    SELECT id INTO teacher_user_id FROM public.users WHERE email = 'budi@example.com' LIMIT 1;
    
    -- Insert teacher details
    INSERT INTO public.teachers (user_id, nip, phone, address)
    VALUES (teacher_user_id, '1234567890', '081234567890', 'Jl. Pendidikan No. 123, Jakarta');
    
    -- Insert sample class
    INSERT INTO public.classes (name, level)
    VALUES ('XI IPA 1', 'SMA');
    
    -- Insert sample subject
    INSERT INTO public.subjects (name, description)
    VALUES ('Matematika', 'Pelajaran Matematika Kelas XI');
END $$;
```

5. Klik tombol **Run** (atau tekan Ctrl+Enter) untuk menjalankan query
6. Tunggu sampai query selesai dijalankan, akan muncul pesan sukses di bagian bawah

## 3. Memeriksa Tabel yang Sudah Dibuat

1. Di menu sebelah kiri, klik **Table Editor**
2. Anda akan melihat daftar tabel yang baru saja dibuat
3. Klik pada tabel untuk melihat strukturnya dan data yang telah dimasukkan

## 4. Mengatur Row Level Security (RLS)

Untuk keamanan data, aktifkan Row Level Security:

1. Di Table Editor, pilih tabel (misalnya `users`)
2. Klik tab **Authentication**
3. Aktifkan **Row Level Security (RLS)** dengan menggeser tombol ke posisi aktif
4. Klik **Save**

Ulangi langkah tersebut untuk semua tabel.

## 5. Membuat Policy untuk Setiap Tabel

Contoh untuk tabel `users`:

1. Masih di tab Authentication tabel `users`
2. Klik **New Policy**
3. Pilih template **Allow full access for authenticated users**
4. Klik **Use this template**
5. Konfigurasi:
   - Policy name: `Allow authenticated full access`
   - Allowed operations: Centang semua (SELECT, INSERT, UPDATE, DELETE)
   - Target roles: `authenticated`
   - Using expression: `true`
6. Klik **Save policy**

Ulangi langkah yang sama untuk semua tabel lainnya.

## 6. Mendapatkan Password Database

Untuk mengisi `DB_PASSWORD` di file `.env`:

1. Di menu sebelah kiri, klik **Project Settings**
2. Klik tab **Database**
3. Scroll ke bawah ke bagian **Connection Pooling**
4. Password database ada di field **Password**
5. Salin password ini dan gunakan di file `.env` untuk nilai `DB_PASSWORD`

## 7. Mengatur API Keys

1. Di menu sebelah kiri, klik **Project Settings**
2. Klik tab **API**
3. Di bagian **Project API keys**:
   - Salin **anon key** (akan digunakan untuk `SUPABASE_KEY` di `.env`)
   - Simpan **service_role key** di tempat yang aman untuk keperluan admin

## Catatan Penting

- Password default untuk semua user sampel adalah `password`
- Row Level Security sangat penting untuk melindungi data Anda
- Pastikan untuk membuat policy yang sesuai dengan kebutuhan aplikasi Anda
- Selalu gunakan koneksi HTTPS saat berinteraksi dengan API Supabase
