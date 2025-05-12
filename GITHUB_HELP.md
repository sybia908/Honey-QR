# Panduan Push ke GitHub

Untuk melakukan push langsung ke GitHub dengan URL yang telah diberikan, ikuti langkah-langkah berikut:

## Menggunakan HTTPS

```bash
git add .
git commit -m "Initial commit untuk Honey QR"
git push -u origin master
```

Ketika diminta kredensial, masukkan:
- Username GitHub Anda
- Password: gunakan Personal Access Token (bukan password GitHub biasa)

## Atau dengan Menyertakan Token di URL

```bash
git push https://USERNAME:PERSONAL_ACCESS_TOKEN@github.com/sybia908/Honey-QR.git master
```

## Menggunakan SSH

Jika menggunakan SSH key yang sudah terhubung dengan GitHub:

```bash
git remote set-url origin git@github.com:sybia908/Honey-QR.git
git push -u origin master
```

## Catatan Penting

- Pastikan repository GitHub `Honey-QR` sudah dibuat di akun GitHub Anda
- Jika ada konflik, gunakan `git pull` terlebih dahulu sebelum push
