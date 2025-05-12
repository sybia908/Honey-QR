<p align="center"><img src="public/images/logo.png" width="200" alt="Honey QR Logo"></p>

<h1 align="center">Honey QR - Aplikasi Presensi dengan QR Code</h1>

<p align="center">
<a href="https://github.com/sybia908/Honey-QR"><img src="https://img.shields.io/badge/GitHub-Honey%20QR-yellow" alt="GitHub"></a>
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-v9.0-red" alt="Laravel"></a>
<a href="https://supabase.com"><img src="https://img.shields.io/badge/Database-Supabase-green" alt="Supabase"></a>
</p>

## Tentang Honey QR

Honey QR adalah aplikasi presensi berbasis QR Code yang didesain untuk memudahkan proses absensi di sekolah. Aplikasi ini memiliki tampilan yang menarik dengan tema honey (madu) dan menyediakan berbagai fitur untuk mengelola presensi siswa dan guru.

## Fitur Utama

- **Manajemen Pengguna**: Admin dapat mengelola pengguna dengan berbagai peran (admin, guru, siswa)
- **Manajemen Kelas**: Membuat dan mengelola kelas-kelas
- **Manajemen Guru**: Mengelola data guru
- **Manajemen Mata Pelajaran**: Mengelola mata pelajaran yang diajarkan
- **Pembuatan QR Code**: Guru dapat membuat QR Code untuk setiap sesi presensi
- **Scan QR Code**: Siswa dapat melakukan scan QR Code untuk presensi
- **Laporan Presensi**: Melihat dan mengekspor laporan presensi
- **Tema Honey**: Tampilan menarik dengan tema honey yang responsif
- **Mode Gelap/Terang**: Dukungan untuk mode gelap dan terang

## Teknologi yang Digunakan

- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5, Tailwind CSS
- **Backend**: Laravel 9
- **Database**: PostgreSQL (Supabase)
- **Penyimpanan**: Supabase Storage
- **Hosting**: GitHub Pages

## Instalasi

### Prasyarat

- PHP 8.0 atau lebih tinggi
- Composer
- Node.js dan NPM
- PostgreSQL

### Langkah Instalasi

1. Clone repository ini
   ```bash
   git clone https://github.com/sybia908/Honey-QR.git
   cd Honey-QR
   ```

2. Instal dependensi PHP
   ```bash
   composer install
   ```

3. Instal dependensi JavaScript
   ```bash
   npm install
   ```

4. Salin file .env.example menjadi .env dan sesuaikan konfigurasi
   ```bash
   cp .env.example .env
   ```

5. Generate application key
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi database
   ```bash
   php artisan migrate --seed
   ```

7. Compile asset
   ```bash
   npm run dev
   ```

8. Jalankan server
   ```bash
   php artisan serve
   ```

## Konfigurasi Supabase

Aplikasi ini menggunakan Supabase sebagai database. Untuk mengkonfigurasi koneksi ke Supabase:

1. Buat akun di [Supabase](https://supabase.com)
2. Buat project baru
3. Dapatkan URL dan API Key dari project settings
4. Perbarui file .env dengan informasi Supabase:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=db.dhpogmrulnvmkhdzjpqh.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your_password

   SUPABASE_URL=https://dhpogmrulnvmkhdzjpqh.supabase.co
   SUPABASE_KEY=your_supabase_key
   ```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
