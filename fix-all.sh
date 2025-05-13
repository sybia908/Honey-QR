#!/bin/bash

# Script perbaikan menyeluruh untuk aplikasi Honey QR di Ubuntu Server dengan Nginx dan MariaDB
#
# CARA PENGGUNAAN:
# 1. Upload script ini ke server Ubuntu Anda
# 2. Jalankan perintah berikut:
#    cd /var/www/Honey-QR  (sesuaikan dengan direktori aplikasi Anda)
#    sudo chmod +x fix-all.sh
#    sudo ./fix-all.sh
#
# PENTING: Script ini harus dijalankan dengan sudo

echo "======================================================================="
echo "=     SCRIPT PERBAIKAN MENYELURUH APLIKASI HONEY QR (NGINX/MARIADB)   ="
echo "======================================================================="

# Tentukan variabel
APP_DIR=$(pwd)
WEB_USER="www-data"
WEB_GROUP="www-data"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_DIR="${APP_DIR}/backup_${DATE}"

echo "Direktori aplikasi: $APP_DIR"
echo "Backup akan disimpan di: $BACKUP_DIR"

# 1. Buat direktori backup
mkdir -p "$BACKUP_DIR"
mkdir -p "$BACKUP_DIR/config"
mkdir -p "$BACKUP_DIR/views"

# 2. Backup file-file penting
echo "Membuat backup file-file penting..."
if [ -f "$APP_DIR/.env" ]; then
    cp "$APP_DIR/.env" "$BACKUP_DIR/.env"
fi
cp "$APP_DIR/config/app.php" "$BACKUP_DIR/config/app.php"
cp "$APP_DIR/config/database.php" "$BACKUP_DIR/config/database.php"
cp -r "$APP_DIR/resources/views/layouts" "$BACKUP_DIR/views/"

# 3. Perbaiki file .env untuk memastikan MySQL/MariaDB digunakan dengan benar
echo "Memeriksa dan memperbaiki file .env..."
if [ -f "$APP_DIR/.env" ]; then
    # Pastikan MySQL/MariaDB dikonfigurasi dengan benar
    sed -i 's/DB_CONNECTION=pgsql/DB_CONNECTION=mysql/g' "$APP_DIR/.env"
    sed -i 's/DB_PORT=5432/DB_PORT=3306/g' "$APP_DIR/.env"
    
    # Pastikan APP_URL dikonfigurasi dengan benar
    SERVER_IP=$(hostname -I | awk '{print $1}')
    if grep -q "APP_URL=" "$APP_DIR/.env"; then
        # Jika domain khusus sudah diatur, jangan ubah
        if grep -q "APP_URL=http://localhost" "$APP_DIR/.env" || grep -q "APP_URL=http://127.0.0.1" "$APP_DIR/.env"; then
            sed -i "s|APP_URL=http://localhost.*|APP_URL=http://${SERVER_IP}|g" "$APP_DIR/.env"
            sed -i "s|APP_URL=http://127.0.0.1.*|APP_URL=http://${SERVER_IP}|g" "$APP_DIR/.env"
        fi
    else
        echo "APP_URL=http://${SERVER_IP}" >> "$APP_DIR/.env"
    fi
else
    echo "File .env tidak ditemukan! Membuat dari template..."
    cp "$APP_DIR/.env.ubuntu" "$APP_DIR/.env"
    SERVER_IP=$(hostname -I | awk '{print $1}')
    sed -i "s|APP_URL=http://your-domain.com|APP_URL=http://${SERVER_IP}|g" "$APP_DIR/.env"
    php "$APP_DIR/artisan" key:generate
fi

# 4. Perbaiki file konfigurasi database untuk memastikan MySQL/MariaDB digunakan dengan benar
echo "Memperbaiki konfigurasi database..."
DB_CONFIG="$APP_DIR/config/database.php"
cp "$DB_CONFIG" "$DB_CONFIG.bak"

# Pastikan MySQL adalah driver default
sed -i "s/'default' => env('DB_CONNECTION', 'pgsql')/'default' => env('DB_CONNECTION', 'mysql')/g" "$DB_CONFIG"

# 5. Perbaiki file layout untuk mengatasi masalah tampilan menu
echo "Memperbaiki file layout untuk mengatasi masalah tampilan menu..."

# 5.1 Perbaiki app.blade.php
LAYOUT_FILE="$APP_DIR/resources/views/layouts/app.blade.php"
if [ -f "$LAYOUT_FILE" ]; then
    cp "$LAYOUT_FILE" "$LAYOUT_FILE.bak"
    
    # Perbaiki akses ke roles yang mungkin null
    sed -i 's/<small class="ms-2 text-muted" style="opacity: 0.8;">{{ auth()->user()->roles->first()->name }}<\/small>/<small class="ms-2 text-muted" style="opacity: 0.8;">{{ auth()->user()->roles->first() ? auth()->user()->roles->first()->name : "User" }}<\/small>/g' "$LAYOUT_FILE"
    
    # Pastikan menu hanya ditampilkan jika pengguna memiliki role yang sesuai
    sed -i 's/@hasanyrole/@hasrole/g' "$LAYOUT_FILE"
    sed -i 's/@endhasanyrole/@endhasrole/g' "$LAYOUT_FILE"
    
    # Tambahkan pengecekan jika mendapatkan error null pada bagian menu
    MENU_PATTERNS=(
        's/@can(\([^)]*\))/@auth\n                                @can\1/g'
        's/@endcan/@endcan\n                                @endauth/g'
    )
    
    for pattern in "${MENU_PATTERNS[@]}"; do
        sed -i "$pattern" "$LAYOUT_FILE"
    done
fi

# 6. Perbaiki file student.blade.php
STUDENT_VIEW="$APP_DIR/resources/views/dashboard/student.blade.php"
if [ -f "$STUDENT_VIEW" ]; then
    cp "$STUDENT_VIEW" "$STUDENT_VIEW.bak"
    
    # Tambahkan pengecekan untuk student yang bisa jadi null
    sed -i 's/{{ auth()->user()->student->nis }}/{{ auth()->user()->student ? auth()->user()->student->nis : "-" }}/g' "$STUDENT_VIEW"
    sed -i 's/{{ auth()->user()->student->class->name }}/{{ auth()->user()->student && auth()->user()->student->class ? auth()->user()->student->class->name : "-" }}/g' "$STUDENT_VIEW"
    sed -i 's/{{ auth()->user()->student->class->major }}/{{ auth()->user()->student && auth()->user()->student->class ? auth()->user()->student->class->major : "-" }}/g' "$STUDENT_VIEW"
fi

# 7. Hapus cache untuk memastikan perubahan diterapkan
echo "Menghapus cache..."
php "$APP_DIR/artisan" cache:clear
php "$APP_DIR/artisan" config:clear
php "$APP_DIR/artisan" route:clear
php "$APP_DIR/artisan" view:clear
php "$APP_DIR/artisan" optimize:clear

# 8. Perbaiki izin file dan direktori
echo "Memperbaiki izin file dan direktori..."
chown -R "$WEB_USER:$WEB_GROUP" "$APP_DIR"
find "$APP_DIR" -type f -not -path "$APP_DIR/storage/*" -not -path "$APP_DIR/bootstrap/cache/*" -exec chmod 644 {} \;
find "$APP_DIR" -type d -not -path "$APP_DIR/storage/*" -not -path "$APP_DIR/bootstrap/cache/*" -exec chmod 755 {} \;

# Pastikan direktori storage dan cache dapat ditulis
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"
chmod -R 775 "$APP_DIR/public"

# 9. Jalankan migrasi dan seed jika perlu
echo "Apakah Anda ingin menjalankan migrasi database? (y/n)"
read -r run_migration

if [ "$run_migration" = "y" ]; then
    echo "Menjalankan migrasi database..."
    php "$APP_DIR/artisan" migrate --force
    
    echo "Apakah Anda ingin mengisi database dengan data awal? (y/n)"
    read -r run_seed
    
    if [ "$run_seed" = "y" ]; then
        echo "Mengisi database dengan data awal..."
        php "$APP_DIR/artisan" db:seed --class=RoleAndPermissionSeeder --force
        php "$APP_DIR/artisan" db:seed --class=AdminUserSeeder --force
    fi
fi

# 10. Perbaiki konfigurasi Nginx
echo "Memperbaiki konfigurasi Nginx..."
NGINX_CONF="/etc/nginx/sites-available/honey-qr"

if [ -f "$NGINX_CONF" ]; then
    cp "$NGINX_CONF" "$NGINX_CONF.bak"
    
    # Sesuaikan nama domain jika diperlukan
    SERVER_IP=$(hostname -I | awk '{print $1}')
    sed -i "s/server_name your-domain.com www.your-domain.com;/server_name $SERVER_IP;/g" "$NGINX_CONF"
    
    # Periksa versi PHP yang diinstal
    PHP_VERSION=""
    if [ -S "/var/run/php/php8.1-fpm.sock" ]; then
        PHP_VERSION="8.1"
    elif [ -S "/var/run/php/php8.2-fpm.sock" ]; then
        PHP_VERSION="8.2"
    elif [ -S "/var/run/php/php8.0-fpm.sock" ]; then
        PHP_VERSION="8.0"
    elif [ -S "/var/run/php/php7.4-fpm.sock" ]; then
        PHP_VERSION="7.4"
    fi
    
    if [ -n "$PHP_VERSION" ]; then
        echo "Ditemukan PHP-FPM versi $PHP_VERSION"
        sed -i "s/fastcgi_pass unix:\/var\/run\/php\/php[0-9]\.[0-9]-fpm.sock;/fastcgi_pass unix:\/var\/run\/php\/php${PHP_VERSION}-fpm.sock;/g" "$NGINX_CONF"
    else
        echo "PERINGATAN: Tidak dapat mendeteksi versi PHP-FPM. Harap periksa konfigurasi Nginx secara manual."
    fi
else
    echo "Membuat konfigurasi Nginx baru..."
    cp "$APP_DIR/nginx.conf" "$NGINX_CONF"
    
    # Sesuaikan nama domain
    SERVER_IP=$(hostname -I | awk '{print $1}')
    sed -i "s/server_name your-domain.com www.your-domain.com;/server_name $SERVER_IP;/g" "$NGINX_CONF"
    
    # Periksa versi PHP yang diinstal
    PHP_VERSION=""
    if [ -S "/var/run/php/php8.1-fpm.sock" ]; then
        PHP_VERSION="8.1"
    elif [ -S "/var/run/php/php8.2-fpm.sock" ]; then
        PHP_VERSION="8.2"
    elif [ -S "/var/run/php/php8.0-fpm.sock" ]; then
        PHP_VERSION="8.0"
    elif [ -S "/var/run/php/php7.4-fpm.sock" ]; then
        PHP_VERSION="7.4"
    fi
    
    if [ -n "$PHP_VERSION" ]; then
        echo "Ditemukan PHP-FPM versi $PHP_VERSION"
        sed -i "s/fastcgi_pass unix:\/var\/run\/php\/php[0-9]\.[0-9]-fpm.sock;/fastcgi_pass unix:\/var\/run\/php\/php${PHP_VERSION}-fpm.sock;/g" "$NGINX_CONF"
    else
        echo "PERINGATAN: Tidak dapat mendeteksi versi PHP-FPM. Harap periksa konfigurasi Nginx secara manual."
    fi
    
    # Aktifkan konfigurasi Nginx
    if [ ! -f "/etc/nginx/sites-enabled/honey-qr" ]; then
        ln -s "$NGINX_CONF" "/etc/nginx/sites-enabled/honey-qr"
    fi
fi

# 11. Restart layanan
echo "Merestart layanan web server..."
systemctl restart php*-fpm
systemctl restart nginx

echo "======================================================================="
echo "=                    PERBAIKAN SELESAI                                ="
echo "======================================================================="
echo ""
echo "Backup file-file penting telah disimpan di: $BACKUP_DIR"
echo ""
echo "Jika aplikasi masih bermasalah, silakan periksa log berikut:"
echo "- Log Nginx: /var/log/nginx/error.log"
echo "- Log Laravel: $APP_DIR/storage/logs/laravel.log"
echo ""
echo "Untuk menguji aplikasi, buka di browser: http://$SERVER_IP"
echo ""
echo "Jika masih bermasalah, jalankan perintah berikut untuk informasi debugging:"
echo "tail -n 100 $APP_DIR/storage/logs/laravel.log"
echo "atau"
echo "tail -n 100 /var/log/nginx/error.log"
