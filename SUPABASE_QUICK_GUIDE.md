# Panduan Cepat Deployment ke Supabase

## 1. Membuat Tabel di Supabase

1. Buka [Supabase Dashboard](https://app.supabase.com)
2. Pilih project dengan URL: `https://dhpogmrulnvmkhdzjpqh.supabase.co`
3. Klik menu **SQL Editor** di sidebar kiri
4. Klik **+ New Query**
5. Copy-paste SQL berikut:

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
```

6. Klik tombol **Run** untuk menjalankan query

## 2. Mengaktifkan Row Level Security (RLS)

1. Klik menu **Table Editor** di sidebar kiri
2. Untuk setiap tabel yang sudah dibuat:
   - Klik nama tabel
   - Klik tab **Authentication**
   - Aktifkan **Row Level Security (RLS)** dengan menggeser toggle
   - Klik **Save**

3. Buat Policy untuk setiap tabel:
   - Masih di tab Authentication, klik **New Policy**
   - Pilih template **Allow full access for authenticated users**
   - Klik **Use this template**
   - Isi Policy name: `Allow authenticated full access`
   - Centang semua operasi (SELECT, INSERT, UPDATE, DELETE)
   - Target roles: `authenticated`
   - Using expression: `true`
   - Klik **Save policy**

## 3. Menggunakan Bucket Storage

Anda sudah berhasil membuat bucket storage `honeyqr-storage`. Untuk menggunakannya:

1. Klik menu **Storage** di sidebar kiri
2. Klik bucket `honeyqr-storage`
3. Klik **Upload File** untuk menguji upload file

## 4. Mengupdate .env dengan Kredensial Supabase

Pastikan file `.env` Anda berisi:

```
DB_CONNECTION=pgsql
DB_HOST=db.dhpogmrulnvmkhdzjpqh.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=G4l4xymini

SUPABASE_URL=https://dhpogmrulnvmkhdzjpqh.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac
```
