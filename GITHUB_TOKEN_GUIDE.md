# Panduan Membuat Personal Access Token GitHub

Berikut ini adalah langkah-langkah untuk membuat Personal Access Token di GitHub:

1. Login ke akun GitHub Anda di [github.com](https://github.com)
2. Klik foto profil Anda di pojok kanan atas, lalu pilih **Settings**
3. Scroll ke bawah dan pilih **Developer settings** di sidebar kiri
4. Pilih **Personal access tokens** > **Tokens (classic)**
5. Klik **Generate new token** > **Generate new token (classic)**
6. Berikan nama pada token di field **Note** (misalnya "Honey QR Deployment")
7. Pilih **Expiration** (misalnya 30 hari)
8. Pilih scope yang diperlukan:
   - `repo` (semua checkbox di bawah repo)
   - `workflow`
   - `read:packages`
   - `write:packages`
9. Klik **Generate token**
10. **PENTING**: Salin token yang dihasilkan. Token ini hanya ditampilkan sekali!

## Cara Menggunakan Token untuk Push

Setelah mendapatkan token, gunakan salah satu cara berikut untuk melakukan push:

### Cara 1: Menyimpan Kredensial Secara Permanen

```bash
git config --global credential.helper store
git push -u origin master
```

Kemudian masukkan username GitHub Anda dan token sebagai password.

### Cara 2: Menggunakan Token dalam URL Remote

```bash
git remote set-url origin https://USERNAME:TOKEN@github.com/sybia908/Honey-QR.git
git push -u origin master
```

Ganti `USERNAME` dengan username GitHub Anda dan `TOKEN` dengan token yang sudah dibuat.

### Cara 3: Gunakan Command Berikut untuk Push

```bash
git push https://USERNAME:TOKEN@github.com/sybia908/Honey-QR.git master
```

## Catatan Penting

- Jangan pernah membagikan token Anda kepada siapapun
- Token memiliki akses ke repository Anda, jadi jagalah kerahasiaannya
- Jika token tidak sengaja terekspos, segera hapus dan buat token baru di GitHub
