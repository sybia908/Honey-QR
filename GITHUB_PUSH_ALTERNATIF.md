# Cara Alternatif Push ke GitHub

Jika Anda mengalami masalah izin saat push ke GitHub, berikut adalah beberapa cara alternatif yang bisa dicoba:

## 1. Menggunakan Personal Access Token (PAT)

```bash
# Hapus remote yang ada (jika perlu)
git remote remove origin

# Tambahkan remote baru dengan token
git remote add origin https://sybia908:YOUR_PERSONAL_ACCESS_TOKEN@github.com/sybia908/Honey-QR.git

# Push ke GitHub
git push -u origin main
```

## 2. Menggunakan Repository Milik Sendiri

Jika Anda ingin menggunakan repository di akun GitHub Anda sendiri:

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote baru dengan akun Anda
git remote add origin https://github.com/NAMA_AKUN_ANDA/Honey-QR.git

# Push ke GitHub
git push -u origin main
```

## 3. Menggunakan SSH (Jika Sudah Dikonfigurasi)

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote SSH
git remote add origin git@github.com:sybia908/Honey-QR.git

# Push ke GitHub
git push -u origin main
```

## 4. Menggunakan GitHub CLI

Jika Anda memiliki GitHub CLI terinstal:

```bash
# Login ke GitHub
gh auth login

# Push ke GitHub
git push -u origin main
```

## Catatan Penting

- Pastikan Anda memiliki izin untuk push ke repository target
- Jika menggunakan akun GitHub sendiri, pastikan repository sudah dibuat terlebih dahulu
- Untuk Personal Access Token, pastikan token memiliki scope 'repo' yang diperlukan
