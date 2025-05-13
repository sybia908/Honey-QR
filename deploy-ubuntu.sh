#!/bin/bash

# Script untuk deployment aplikasi Honey QR di Ubuntu Server
# dengan Nginx dan MariaDB

echo "===== Script Deployment Honey QR ====="
echo "Memulai proses deployment..."

# Pastikan script dijalankan sebagai root
if [ "$(id -u)" != "0" ]; then
   echo "Script ini harus dijalankan sebagai root" 1>&2
   exit 1
fi

# Update dan upgrade sistem
echo "Memperbarui sistem..."
apt update
apt upgrade -y

# Tambahkan repository PHP
echo "Menambahkan repository PHP..."
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

# Pilih versi PHP
echo "Pilih versi PHP yang akan digunakan:"
echo "1) PHP 8.1 (default)"
echo "2) PHP 8.2"
read -p "Pilihan [1/2]: " php_version
php_version=${php_version:-1}

if [ "$php_version" = "1" ]; then
    PHP_VER="8.1"
    echo "Menggunakan PHP 8.1"
else
    PHP_VER="8.2"
    echo "Menggunakan PHP 8.2"
fi

# Instal paket yang diperlukan
echo "Menginstal paket yang diperlukan..."
apt install -y nginx mariadb-server php$PHP_VER-fpm php$PHP_VER-cli php$PHP_VER-common php$PHP_VER-mysql \
    php$PHP_VER-zip php$PHP_VER-gd php$PHP_VER-mbstring php$PHP_VER-curl php$PHP_VER-xml php$PHP_VER-bcmath \
    php$PHP_VER-intl php$PHP_VER-pgsql unzip git

# Konfigurasi direktori aplikasi
echo "Menyiapkan direktori aplikasi..."
mkdir -p /var/www/honey-qr
chown -R www-data:www-data /var/www/honey-qr

# Salin file aplikasi (asumsikan file aplikasi sudah ada di direktori saat ini)
echo "Menyalin file aplikasi..."
cp -R ./* /var/www/honey-qr/
cd /var/www/honey-qr

# Instal Composer jika belum ada
if ! [ -x "$(command -v composer)" ]; then
  echo "Menginstal Composer..."
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# Instal dependensi dengan Composer
echo "Menginstal dependensi aplikasi..."
cd /var/www/honey-qr
sudo -u www-data composer install --no-dev --optimize-autoloader

# Konfigurasi .env
echo "Menyiapkan file konfigurasi..."
sudo -u www-data cp .env.ubuntu .env

# Generate app key
echo "Menghasilkan application key..."
sudo -u www-data php artisan key:generate

# Tanya informasi database
echo "Konfigurasi database MariaDB:"
read -p "Nama database (default: honey_qr): " db_name
db_name=${db_name:-honey_qr}

read -p "Username database (default: honey_qr_user): " db_user
db_user=${db_user:-honey_qr_user}

read -p "Password database: " db_password
if [ -z "$db_password" ]; then
    echo "Password tidak boleh kosong!"
    exit 1
fi

read -p "Domain aplikasi (tanpa http/https): " domain_name
if [ -z "$domain_name" ]; then
    echo "Domain tidak boleh kosong!"
    exit 1
fi

# Buat database dan user
echo "Membuat database dan user..."
mysql -e "CREATE DATABASE IF NOT EXISTS $db_name;"
mysql -e "CREATE USER IF NOT EXISTS '$db_user'@'localhost' IDENTIFIED BY '$db_password';"
mysql -e "GRANT ALL PRIVILEGES ON $db_name.* TO '$db_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Update file .env
echo "Memperbarui konfigurasi .env..."
sed -i "s/DB_DATABASE=honey_qr/DB_DATABASE=$db_name/" .env
sed -i "s/DB_USERNAME=honey_qr_user/DB_USERNAME=$db_user/" .env
sed -i "s/DB_PASSWORD=your_secure_password/DB_PASSWORD=$db_password/" .env
sed -i "s/APP_URL=http:\/\/your-domain.com/APP_URL=http:\/\/$domain_name/" .env

# Migrasi database dan optimasi
echo "Melakukan migrasi database dan optimasi aplikasi..."
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Konfigurasi Nginx
echo "Menyiapkan konfigurasi Nginx..."
cat > /etc/nginx/sites-available/honey-qr << EOF
server {
    listen 80;
    server_name $domain_name www.$domain_name;
    root /var/www/honey-qr/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VER}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Aktifkan konfigurasi Nginx
echo "Mengaktifkan konfigurasi Nginx..."
ln -sf /etc/nginx/sites-available/honey-qr /etc/nginx/sites-enabled/
nginx -t

# Restart layanan
echo "Merestart layanan..."
systemctl restart php${PHP_VER}-fpm
systemctl restart nginx

# Atur izin file
echo "Mengatur izin file..."
chown -R www-data:www-data /var/www/honey-qr
find /var/www/honey-qr -type f -exec chmod 644 {} \;
find /var/www/honey-qr -type d -exec chmod 755 {} \;
chmod -R 775 /var/www/honey-qr/storage /var/www/honey-qr/bootstrap/cache

# Tanya apakah ingin menginstal SSL
read -p "Apakah Anda ingin menginstal SSL dengan Certbot? (y/n): " install_ssl
if [ "$install_ssl" = "y" ] || [ "$install_ssl" = "Y" ]; then
    echo "Menginstal Certbot dan mengkonfigurasi SSL..."
    apt install -y certbot python3-certbot-nginx
    certbot --nginx -d $domain_name -d www.$domain_name
fi

echo "===== Deployment Selesai ====="
echo "Aplikasi Honey QR berhasil di-deploy!"
echo "Anda dapat mengakses aplikasi di: http://$domain_name"
if [ "$install_ssl" = "y" ] || [ "$install_ssl" = "Y" ]; then
    echo "atau https://$domain_name (dengan SSL)"
fi
echo "Terima kasih telah menggunakan script deployment ini."
