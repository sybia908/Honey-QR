# Panduan Perbaikan Menyeluruh Aplikasi Honey QR

Script `fix-all.sh` telah dibuat untuk memperbaiki seluruh masalah aplikasi Honey QR pada deployment dengan Nginx dan MariaDB. Script ini akan memperbaiki berbagai masalah termasuk menu dan fitur yang tidak berfungsi.

## Cara Penggunaan

1. Login ke server Ubuntu Anda menggunakan SSH
2. Pindah ke direktori aplikasi:
   ```bash
   cd /var/www/Honey-QR
   ```
3. Download script terbaru:
   ```bash
   sudo wget https://raw.githubusercontent.com/sybia908/Honey-QR/main/fix-all.sh
   ```
4. Beri izin eksekusi:
   ```bash
   sudo chmod +x fix-all.sh
   ```
5. Jalankan script:
   ```bash
   sudo ./fix-all.sh
   ```
6. Ikuti instruksi yang ditampilkan selama proses perbaikan

## Apa yang Diperbaiki Script Ini

Script `fix-all.sh` akan memperbaiki berbagai masalah secara otomatis:

1. **Konfigurasi Database**
   - Memastikan aplikasi menggunakan MySQL/MariaDB dengan benar
   - Memperbaiki konfigurasi koneksi database

2. **Tampilan dan Menu**
   - Memperbaiki tampilan menu yang tidak muncul
   - Mengatasi error "Attempt to read property on null"
   - Memperbaiki file layout untuk menu dan navigasi

3. **Izin File**
   - Memperbaiki izin file dan direktori
   - Memastikan direktori storage dan cache dapat diakses dengan benar

4. **Konfigurasi Nginx**
   - Menyesuaikan konfigurasi Nginx dengan alamat IP server
   - Mendeteksi dan menggunakan versi PHP-FPM yang tepat

5. **Cache dan Optimasi**
   - Membersihkan cache aplikasi
   - Mengoptimalkan aplikasi untuk kinerja lebih baik

## Setelah Menjalankan Script

Setelah menjalankan script, akses aplikasi Anda melalui browser:
```
http://[alamat-ip-server]
```

Login dengan kredensial:
- **Username:** afrils
- **Password:** G4l4xymini

## Pemecahan Masalah

Jika masih mengalami masalah setelah menjalankan script:

1. Periksa log Laravel:
   ```bash
   sudo tail -n 100 /var/www/Honey-QR/storage/logs/laravel.log
   ```

2. Periksa log Nginx:
   ```bash
   sudo tail -n 100 /var/log/nginx/error.log
   ```

3. Pastikan layanan Nginx dan PHP-FPM berjalan:
   ```bash
   sudo systemctl status nginx
   sudo systemctl status php*-fpm
   ```

## Backup

Script secara otomatis membuat backup file-file penting sebelum melakukan perubahan. Backup disimpan di direktori:
```
/var/www/Honey-QR/backup_[tanggal-waktu]
```

Anda dapat mengembalikan file dari backup jika diperlukan.
