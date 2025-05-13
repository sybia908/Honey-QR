#!/bin/bash

# Script untuk memperbaiki masalah git pull
# yang terjadi karena ada perubahan lokal yang belum di-commit
#
# CARA PENGGUNAAN:
# 1. Upload script ini ke server Ubuntu Anda
# 2. Jalankan perintah berikut:
#    cd /var/www/Honey-QR  (sesuaikan dengan direktori aplikasi Anda)
#    sudo chmod +x git-fix.sh
#    sudo ./git-fix.sh
#
# PENTING: Script ini harus dijalankan dengan sudo

echo "===== Script Perbaikan Git Pull Honey QR ====="

# 1. Backup file yang bermasalah
echo "Membuat backup file fix-login.sh..."
cp fix-login.sh fix-login.sh.bak

# 2. Reset perubahan lokal
echo "Mereset perubahan lokal pada file fix-login.sh..."
git checkout -- fix-login.sh

# 3. Pull perubahan terbaru
echo "Mengambil perubahan terbaru dari repository..."
git pull

# 4. Download emergency-fix.sh
echo "Memastikan emergency-fix.sh tersedia..."
if [ ! -f emergency-fix.sh ]; then
    echo "Mengunduh emergency-fix.sh..."
    wget -q https://raw.githubusercontent.com/sybia908/Honey-QR/main/emergency-fix.sh
    chmod +x emergency-fix.sh
fi

echo "===== Perbaikan Selesai ====="
echo "Perubahan terbaru berhasil diambil dari repository."
echo "File fix-login.sh yang asli telah di-backup ke fix-login.sh.bak"
echo ""
echo "Untuk menjalankan script perbaikan darurat, jalankan:"
echo "sudo ./emergency-fix.sh"
