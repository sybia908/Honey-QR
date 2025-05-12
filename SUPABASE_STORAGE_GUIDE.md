# Panduan Membuat Bucket Storage di Supabase

Berikut adalah langkah-langkah untuk membuat bucket storage di Supabase untuk menyimpan file dari aplikasi Honey QR:

## 1. Login ke Supabase

1. Buka browser dan navigasi ke [app.supabase.com](https://app.supabase.com)
2. Login menggunakan kredensial Anda

## 2. Buka Project

1. Dari dashboard, pilih project Anda (dengan URL: db.dhpogmrulnvmkhdzjpqh.supabase.co)
2. Ini akan membawa Anda ke dashboard project

## 3. Buat Bucket Storage

1. Di menu sebelah kiri, klik **Storage**
2. Klik tombol **Create a new bucket**
3. Masukkan nama bucket: `honeyqr-storage`
4. Centang opsi **Make bucket public** untuk memungkinkan akses public ke file yang diupload
5. Klik **Create bucket** untuk membuat bucket

## 4. Konfigurasi Kebijakan Akses (Access Policies)

Untuk memastikan kontrol akses yang tepat, kita perlu mengatur kebijakan akses:

1. Setelah bucket dibuat, klik bucket `honeyqr-storage` untuk membukanya
2. Klik tab **Policies**
3. Klik **Create policy**
4. Pilih template **Insert (Create)**, lalu klik **Use this template**
5. Konfigurasikan policy sebagai berikut:
   - Policy name: `Allow public uploads`
   - Allowed operation: `INSERT`
   - Target roles: `authenticated, anon`
   - Expression: `true`
6. Klik **Save policy**

7. Buat policy baru lagi dengan klik **Create policy**
8. Pilih template **Select (Read)**, lalu klik **Use this template**
9. Konfigurasikan policy sebagai berikut:
   - Policy name: `Allow public download`
   - Allowed operation: `SELECT`
   - Target roles: `authenticated, anon`
   - Expression: `true`
10. Klik **Save policy**

11. Buat policy baru lagi dengan klik **Create policy**
12. Pilih template **Update**, lalu klik **Use this template**
13. Konfigurasikan policy sebagai berikut:
    - Policy name: `Allow authenticated updates`
    - Allowed operation: `UPDATE`
    - Target roles: `authenticated`
    - Expression: `true`
14. Klik **Save policy**

15. Buat policy baru lagi dengan klik **Create policy**
16. Pilih template **Delete**, lalu klik **Use this template**
17. Konfigurasikan policy sebagai berikut:
    - Policy name: `Allow authenticated deletes`
    - Allowed operation: `DELETE`
    - Target roles: `authenticated`
    - Expression: `true`
18. Klik **Save policy**

## 5. Uji Upload File

Untuk menguji apakah bucket storage Anda sudah berfungsi dengan baik:

1. Kembali ke halaman bucket `honeyqr-storage`
2. Klik tombol **Upload**
3. Pilih file dari komputer Anda
4. Klik **Upload** untuk mengunggah file
5. Setelah terupload, file akan muncul di daftar dan memiliki URL publik yang dapat diakses

## 6. Integrasi dengan Aplikasi Laravel

Setelah bucket storage dibuat, aplikasi Laravel dapat menggunakan Supabase Storage API untuk:

1. Upload file
2. Download file
3. Mengelola file yang disimpan di bucket

Semua ini sudah terimplementasi di `SupabaseService` yang telah kita buat.

## Catatan Penting

- File yang disimpan dalam bucket `public` dapat diakses oleh siapa saja yang memiliki URL file tersebut
- Untuk file yang bersifat sensitif atau pribadi, pertimbangkan untuk membuat bucket terpisah dengan kebijakan akses yang lebih ketat
- Pastikan untuk tidak menyimpan informasi sensitif dalam nama file
