#!/bin/bash

# Script untuk memperbaiki masalah izin pada aplikasi Laravel di Ubuntu Server
#
# CARA PENGGUNAAN:
# 1. Upload script ini ke server Ubuntu Anda
# 2. Jalankan perintah berikut:
#    cd /var/www/Honey-QR  (sesuaikan dengan direktori aplikasi Anda)
#    sudo chmod +x fix-permissions.sh
#    sudo ./fix-permissions.sh
#
# PENTING: Script ini harus dijalankan dengan sudo

echo "===== Script Perbaikan Izin File Laravel ====="

# Tentukan variabel
APP_DIR=$(pwd)
WEB_USER="www-data"
WEB_GROUP="www-data"

echo "Direktori aplikasi: $APP_DIR"
echo "Web user/group: $WEB_USER:$WEB_GROUP"

# 1. Perbaiki izin direktori storage
echo "Memperbaiki izin direktori storage..."
mkdir -p $APP_DIR/storage/logs
touch $APP_DIR/storage/logs/laravel.log
chown -R $WEB_USER:$WEB_GROUP $APP_DIR/storage
chmod -R 775 $APP_DIR/storage

# 2. Perbaiki izin direktori bootstrap/cache
echo "Memperbaiki izin direktori bootstrap/cache..."
mkdir -p $APP_DIR/bootstrap/cache
chown -R $WEB_USER:$WEB_GROUP $APP_DIR/bootstrap/cache
chmod -R 775 $APP_DIR/bootstrap/cache

# 3. Perbaiki izin file .env
echo "Memperbaiki izin file .env..."
if [ -f $APP_DIR/.env ]; then
    chown $WEB_USER:$WEB_GROUP $APP_DIR/.env
    chmod 644 $APP_DIR/.env
fi

# 4. Perbaiki izin direktori public
echo "Memperbaiki izin direktori public..."
chown -R $WEB_USER:$WEB_GROUP $APP_DIR/public
chmod -R 755 $APP_DIR/public

# 5. Perbaiki izin file laravel.log
echo "Memperbaiki izin file laravel.log..."
touch $APP_DIR/storage/logs/laravel.log
chown $WEB_USER:$WEB_GROUP $APP_DIR/storage/logs/laravel.log
chmod 664 $APP_DIR/storage/logs/laravel.log

# 6. Perbaiki izin direktori framework
echo "Memperbaiki izin direktori framework..."
mkdir -p $APP_DIR/storage/framework/sessions
mkdir -p $APP_DIR/storage/framework/views
mkdir -p $APP_DIR/storage/framework/cache
chown -R $WEB_USER:$WEB_GROUP $APP_DIR/storage/framework
chmod -R 775 $APP_DIR/storage/framework

# 7. Tambahkan user saat ini ke grup www-data (opsional)
echo "Menambahkan user saat ini ke grup www-data..."
CURRENT_USER=$(whoami)
if [ "$CURRENT_USER" != "root" ]; then
    usermod -a -G $WEB_GROUP $CURRENT_USER
    echo "User $CURRENT_USER ditambahkan ke grup $WEB_GROUP"
fi

# 8. Restart layanan web server
echo "Merestart layanan web server..."
systemctl restart php*-fpm
systemctl restart nginx

# 9. Bersihkan cache Laravel
echo "Membersihkan cache Laravel..."
sudo -u $WEB_USER php $APP_DIR/artisan cache:clear
sudo -u $WEB_USER php $APP_DIR/artisan view:clear
sudo -u $WEB_USER php $APP_DIR/artisan config:clear
sudo -u $WEB_USER php $APP_DIR/artisan route:clear

echo "===== Perbaikan Izin Selesai ====="
echo "Semua izin file dan direktori telah diperbaiki."
echo "Coba akses aplikasi lagi untuk melihat apakah masalah sudah teratasi."
echo ""
echo "Jika masih ada masalah, coba jalankan perintah berikut:"
echo "sudo chmod -R 777 $APP_DIR/storage $APP_DIR/bootstrap/cache"
echo "(Catatan: Perintah di atas adalah solusi sementara dan tidak disarankan untuk lingkungan produksi)"
