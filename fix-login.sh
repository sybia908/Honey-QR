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

# Pastikan APP_URL benar
sed -i "s|APP_URL=http://your-domain.com|APP_URL=http://220.247.171.222:2200|g" .env

# 2. Generate application key jika belum ada
echo "Memeriksa dan menghasilkan application key..."
php artisan key:generate --force

# 3. Bersihkan cache konfigurasi
echo "Membersihkan cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# 4. Reset database dan jalankan migrasi baru
echo "Reset database dan menjalankan migrasi baru..."
php artisan migrate:fresh --force

# 5. Jalankan seeder untuk roles dan permissions TERLEBIH DAHULU
echo "Membuat roles dan permissions..."
php artisan db:seed --class=RoleAndPermissionSeeder --force

# 6. Jalankan seeder untuk membuat user admin
echo "Membuat user admin..."
php artisan db:seed --class=AdminUserSeeder --force

# 7. Periksa dan perbaiki izin storage dan bootstrap/cache
echo "Memeriksa izin direktori storage dan bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Perbaiki masalah session
echo "Memperbaiki masalah session..."
mkdir -p storage/framework/sessions
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions

# 9. Regenerasi cache konfigurasi
echo "Mengoptimalkan aplikasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 10. Restart layanan web server dan PHP-FPM
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
echo ""
echo "Jika masih mendapatkan error 419 page expired, coba jalankan perintah berikut:"
echo "php artisan session:table"
echo "php artisan migrate"
echo "php artisan config:clear"
echo "php artisan cache:clear"
echo "systemctl restart php*-fpm"
echo "systemctl restart nginx"
