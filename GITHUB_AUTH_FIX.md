# Mengatasi Masalah Izin GitHub

Anda mengalami error "Permission denied" saat mencoba push ke repository GitHub. Berikut adalah beberapa cara untuk mengatasinya:

## 1. Gunakan Personal Access Token (PAT)

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote baru dengan token
git remote add origin https://USERNAME:PERSONAL_ACCESS_TOKEN@github.com/sybia908/Honey-QR.git

# Coba push lagi
git push -u origin master
```

Ganti `USERNAME` dengan username GitHub Anda dan `PERSONAL_ACCESS_TOKEN` dengan token yang Anda buat di GitHub.

## 2. Minta Akses ke Repository

Jika Anda menggunakan akun "Afriels" dan ingin mengakses repository "sybia908/Honey-QR.git", Anda perlu:

1. Hubungi pemilik repository (sybia908) untuk menambahkan akun Anda sebagai kolaborator
2. Atau minta pemilik repository untuk memberikan Anda akses push

## 3. Gunakan Repository Milik Sendiri

Jika Anda ingin menggunakan repository milik sendiri:

```bash
# Hapus remote yang ada
git remote remove origin

# Buat repository baru di akun GitHub Anda
# Lalu tambahkan sebagai remote
git remote add origin https://github.com/Afriels/Honey-QR.git

# Coba push lagi
git push -u origin master
```

## 4. Gunakan SSH Key (Jika Sudah Dikonfigurasi)

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote SSH
git remote add origin git@github.com:sybia908/Honey-QR.git

# Coba push lagi
git push -u origin master
```

## Cara Membuat Personal Access Token

1. Login ke GitHub
2. Klik foto profil Anda > Settings
3. Scroll ke bawah dan klik "Developer settings"
4. Klik "Personal access tokens" > "Tokens (classic)"
5. Klik "Generate new token" > "Generate new token (classic)"
6. Beri nama token dan pilih scope "repo"
7. Klik "Generate token" dan salin token yang dihasilkan
