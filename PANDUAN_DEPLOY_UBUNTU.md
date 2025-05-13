# Panduan Deployment Aplikasi Honey QR di Ubuntu Server dengan Nginx dan MariaDB

Panduan ini akan membantu Anda men-deploy aplikasi Honey QR di server Ubuntu menggunakan Nginx sebagai web server dan MariaDB sebagai database.

## Persiapan Server

### 1. Update dan Upgrade Sistem

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Instal Paket yang Diperlukan

```bash
sudo apt install -y nginx mariadb-server php8.1-fpm php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-intl php8.1-pgsql unzip git
```

### 3. Konfigurasi MariaDB

```bash
sudo mysql_secure_installation
```

Ikuti petunjuk untuk mengatur password root dan mengamankan instalasi MariaDB.

### 4. Buat Database dan User untuk Aplikasi

```bash
sudo mysql -u root -p
```

Setelah masuk ke MariaDB, jalankan perintah berikut:

```sql
CREATE DATABASE honey_qr;
CREATE USER 'honey_qr_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON honey_qr.* TO 'honey_qr_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Deployment Aplikasi

### 1. Clone Repository (jika menggunakan Git)

```bash
cd /var/www
sudo git clone https://github.com/sybia908/Honey-QR.git honey-qr
sudo chown -R www-data:www-data honey-qr
cd honey-qr
```

Atau upload file aplikasi ke direktori `/var/www/honey-qr` menggunakan SFTP/SCP.

### 2. Instal Composer dan Dependensi

```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
cd /var/www/honey-qr
sudo -u www-data composer install --no-dev --optimize-autoloader
```

### 3. Konfigurasi Aplikasi

```bash
sudo -u www-data cp .env.ubuntu .env
sudo -u www-data php artisan key:generate
```

Edit file `.env` untuk mengatur konfigurasi database:

```bash
sudo nano .env
```

Pastikan konfigurasi database sudah benar:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=honey_qr
DB_USERNAME=honey_qr_user
DB_PASSWORD=your_secure_password
```

### 4. Migrasi Database dan Optimasi

```bash
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### 5. Konfigurasi Nginx

Salin file konfigurasi Nginx:

```bash
sudo cp /var/www/honey-qr/nginx.conf /etc/nginx/sites-available/honey-qr
```

Edit file konfigurasi untuk menyesuaikan domain:

```bash
sudo nano /etc/nginx/sites-available/honey-qr
```

Aktifkan konfigurasi:

```bash
sudo ln -s /etc/nginx/sites-available/honey-qr /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 6. Atur Izin File

```bash
sudo chown -R www-data:www-data /var/www/honey-qr
sudo find /var/www/honey-qr -type f -exec chmod 644 {} \;
sudo find /var/www/honey-qr -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/honey-qr/storage /var/www/honey-qr/bootstrap/cache
```

## Konfigurasi SSL (Opsional tapi Direkomendasikan)

Untuk mengamankan aplikasi dengan SSL, gunakan Certbot:

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

Ikuti petunjuk untuk menyelesaikan proses sertifikasi SSL.

## Pemeliharaan

### Pembaruan Aplikasi

Untuk memperbarui aplikasi:

```bash
cd /var/www/honey-qr
sudo -u www-data git pull
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### Restart Layanan

Jika diperlukan, restart layanan:

```bash
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx
```

## Pemecahan Masalah

### Memeriksa Log Nginx

```bash
sudo tail -f /var/log/nginx/error.log
```

### Memeriksa Log Laravel

```bash
sudo tail -f /var/www/honey-qr/storage/logs/laravel.log
```

### Izin File

Jika ada masalah izin:

```bash
sudo chown -R www-data:www-data /var/www/honey-qr
sudo chmod -R 775 /var/www/honey-qr/storage /var/www/honey-qr/bootstrap/cache
```

## Kesimpulan

Aplikasi Honey QR sekarang seharusnya sudah berjalan di server Ubuntu Anda dengan Nginx sebagai web server dan MariaDB sebagai database. Pastikan untuk mengganti `your-domain.com` dengan domain Anda yang sebenarnya dan `your_secure_password` dengan password yang kuat.
