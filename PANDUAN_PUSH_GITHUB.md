# Panduan Lengkap Push ke GitHub

Berikut adalah panduan lengkap untuk mengirim proyek Honey QR ke GitHub:

## 1. Persiapan Repository GitHub

Sebelum melakukan push, pastikan Anda telah membuat repository di GitHub:

1. Buka [GitHub](https://github.com) dan login dengan akun Anda
2. Klik tombol "+" di pojok kanan atas, lalu pilih "New repository"
3. Isi nama repository: "Honey-QR"
4. Biarkan repository sebagai "Public"
5. Jangan centang opsi "Initialize this repository with a README"
6. Klik "Create repository"

## 2. Menggunakan Personal Access Token (PAT)

Cara terbaik untuk mengatasi masalah izin GitHub adalah dengan menggunakan Personal Access Token:

### Langkah 1: Buat Personal Access Token

1. Buka [GitHub Settings](https://github.com/settings/tokens)
2. Klik "Generate new token" > "Generate new token (classic)"
3. Beri nama token (misalnya "Honey QR Deployment")
4. Pilih scope "repo" (centang semua opsi di bawahnya)
5. Klik "Generate token"
6. **PENTING**: Salin token yang dihasilkan, token ini hanya ditampilkan sekali!

### Langkah 2: Push dengan Token

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote baru dengan token
git remote add origin https://USERNAME:TOKEN@github.com/USERNAME/Honey-QR.git

# Push ke GitHub
git push -u origin main
```

Ganti `USERNAME` dengan username GitHub Anda dan `TOKEN` dengan Personal Access Token yang sudah dibuat.

## 3. Menggunakan GitHub CLI

Jika Anda memiliki GitHub CLI terinstal:

```bash
# Login ke GitHub
gh auth login

# Push ke GitHub
git push -u origin main
```

## 4. Menggunakan SSH

Jika Anda lebih suka menggunakan SSH:

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote SSH
git remote add origin git@github.com:USERNAME/Honey-QR.git

# Push ke GitHub
git push -u origin main
```

Ganti `USERNAME` dengan username GitHub Anda.

## 5. Menggunakan GitHub Desktop

Alternatif lain adalah menggunakan GitHub Desktop:

1. Download dan instal [GitHub Desktop](https://desktop.github.com/)
2. Buka GitHub Desktop dan login dengan akun GitHub Anda
3. Tambahkan repository lokal (File > Add local repository)
4. Pilih folder proyek Honey QR
5. Klik "Publish repository"
6. Isi nama repository dan klik "Publish repository"

## 6. Verifikasi Deployment

Setelah berhasil push:

1. Buka repository di GitHub
2. Cek tab "Actions" untuk melihat status deployment
3. Setelah deployment selesai, akses aplikasi di:
   - `https://USERNAME.github.io/Honey-QR` (jika menggunakan GitHub Pages)

## Catatan Penting

- Pastikan semua perubahan sudah di-commit sebelum push
- Jika mengalami masalah, coba refresh token GitHub Anda
- Untuk deployment yang lebih mudah di masa depan, simpan kredensial GitHub di komputer Anda
