#!/bin/bash

# Script untuk memperbaiki masalah layout pada aplikasi Honey QR
#
# CARA PENGGUNAAN:
# 1. Upload script ini ke server Ubuntu Anda
# 2. Jalankan perintah berikut:
#    cd /var/www/Honey-QR  (sesuaikan dengan direktori aplikasi Anda)
#    sudo chmod +x fix-layout.sh
#    sudo ./fix-layout.sh
#
# PENTING: Script ini harus dijalankan dengan sudo

echo "===== Script Perbaikan Layout Honey QR ====="

# Tentukan variabel
APP_DIR=$(pwd)
WEB_USER="www-data"
WEB_GROUP="www-data"

echo "Direktori aplikasi: $APP_DIR"

# 1. Perbaiki file app.blade.php
echo "Memperbaiki file app.blade.php..."

# Buat backup file
cp $APP_DIR/resources/views/layouts/app.blade.php $APP_DIR/resources/views/layouts/app.blade.php.bak

# Cari dan ganti baris yang bermasalah
sed -i 's/<small class="ms-2 text-muted" style="opacity: 0.8;">{{ auth()->user()->roles->first()->name }}<\/small>/<small class="ms-2 text-muted" style="opacity: 0.8;">{{ auth()->user()->roles->first() ? auth()->user()->roles->first()->name : "User" }}<\/small>/g' $APP_DIR/resources/views/layouts/app.blade.php

# Perbaiki izin file
chown $WEB_USER:$WEB_GROUP $APP_DIR/resources/views/layouts/app.blade.php
chmod 644 $APP_DIR/resources/views/layouts/app.blade.php

# 2. Hapus cache view
echo "Menghapus cache view..."
rm -rf $APP_DIR/storage/framework/views/*

# 3. Bersihkan cache Laravel
echo "Membersihkan cache Laravel..."
php $APP_DIR/artisan view:clear
php $APP_DIR/artisan cache:clear
php $APP_DIR/artisan config:clear
php $APP_DIR/artisan route:clear
php $APP_DIR/artisan optimize:clear

# 4. Restart layanan web server
echo "Merestart layanan web server..."
systemctl restart php*-fpm
systemctl restart nginx

echo "===== Perbaikan Layout Selesai ====="
echo "File app.blade.php telah diperbaiki dan cache telah dibersihkan."
echo "Coba akses aplikasi lagi untuk melihat apakah masalah sudah teratasi."
echo ""
echo "Jika masih ada masalah, file backup tersedia di:"
echo "$APP_DIR/resources/views/layouts/app.blade.php.bak"
