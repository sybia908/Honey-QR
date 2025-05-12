# Panduan Menambahkan Secrets di GitHub

Untuk menggunakan workflow GitHub Actions dengan kredensial Supabase, Anda perlu menambahkan secrets di repository GitHub Anda. Berikut adalah langkah-langkahnya:

## 1. Buka Repository GitHub

1. Buka browser dan navigasi ke [github.com/sybia908/Honey-QR](https://github.com/sybia908/Honey-QR)
2. Login ke akun GitHub Anda

## 2. Tambahkan Secrets

1. Klik tab **Settings** di repository
2. Di sidebar kiri, klik **Secrets and variables** > **Actions**
3. Klik tombol **New repository secret**

## 3. Tambahkan Secret untuk Password Database Supabase

1. Pada form yang muncul:
   - Name: `SUPABASE_DB_PASSWORD`
   - Secret: `G4l4xymini`
2. Klik **Add secret**

## 4. Tambahkan Secret untuk API Key Supabase

1. Klik tombol **New repository secret** lagi
2. Pada form yang muncul:
   - Name: `SUPABASE_API_KEY`
   - Secret: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRocG9nbXJ1bG52bWtoZHpqcHFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcwNDg3MDcsImV4cCI6MjA2MjYyNDcwN30.n3Kywx5Os9kAKg2a4XwcNaJ14zC1OG7sSfdqzfuWTac`
3. Klik **Add secret**

## 5. Verifikasi Secrets

1. Setelah menambahkan secrets, Anda akan melihat keduanya dalam daftar Repository secrets
2. Secrets ini akan tersedia untuk digunakan dalam workflow GitHub Actions

## Catatan Penting

- Secrets di GitHub tidak dapat dilihat setelah dibuat, hanya dapat diperbarui atau dihapus
- Secrets ini hanya tersedia untuk repository ini saja
- Jika Anda mengubah password database atau API key Supabase, jangan lupa untuk memperbarui secrets di GitHub
