#!/bin/bash

# Script untuk memperbaiki masalah login di aplikasi Honey QR
# yang sudah di-deploy di server Ubuntu

echo "===== Script Perbaikan Login Honey QR ====="

# 1. Pastikan konfigurasi .env sudah benar
echo "Memeriksa konfigurasi .env..."
if [ ! -f .env ]; then
    echo "File .env tidak ditemukan. Menyalin dari .env.ubuntu..."
    cp .env.ubuntu .env
fi

# 2. Generate application key jika belum ada
echo "Memeriksa dan menghasilkan application key..."
php artisan key:generate --force

# 3. Bersihkan cache konfigurasi
echo "Membersihkan cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Jalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force

# 5. Jalankan seeder untuk membuat user admin
echo "Membuat user admin..."
php artisan db:seed --class=AdminUserSeeder --force

# 6. Jalankan seeder untuk roles dan permissions
echo "Membuat roles dan permissions..."
php artisan db:seed --class=RoleAndPermissionSeeder --force

# 7. Periksa izin storage
echo "Memeriksa izin direktori storage..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Regenerasi cache konfigurasi
echo "Mengoptimalkan aplikasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Restart layanan web server dan PHP-FPM
echo "Merestart layanan..."
systemctl restart php*-fpm
systemctl restart nginx

echo "===== Perbaikan Selesai ====="
echo "Coba login dengan kredensial berikut:"
echo "Username: afrils"
echo "Password: G4l4xymini"
echo ""
echo "Jika masih tidak bisa login, jalankan perintah berikut untuk melihat log:"
echo "tail -f storage/logs/laravel.log"
