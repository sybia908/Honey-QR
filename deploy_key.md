# Deploy Key untuk GitHub

Berikut adalah deploy key yang dapat digunakan untuk mengakses repository GitHub:

```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQCg5ogsHaAXN9UhMRtsn/lTk8hZ0QHLqbW39UZ2I3y91MhfBvjeFJW1dPgINWudXwYvolgR31WGRRYcBcLpH4jFzlPv2wOPhfTaFKuIe0gHm4kl6ic37ROG+oah6wqC1XO+nlKMCpUPdFVOZn+7slwMb9hLdEGx/uF5MjPTIwitus0J3FsWKwILRT5AJ4nU2jSaMJUG4+LxxeiNcRwNzGd6dQvkQ7mzGSFm5v2Y4mhT3sjb+7noMNkxoVnC4OLi3p98JmodMTVNo4twuP5xEhWQWRnQeWbhVl2wNf1qX+haKMFGwKdoWnsjwWjBomH/m/uDBMlXrIkeeFLFtvctSfSV76wtLiypCxDiUiXXOnMDu2ge8JbrTut71ORa4nJo7ii0Q9v/Qtjvxvv7PDMzLIZqnl/nhXzfpOr4y4DmGur0W59WjiCpDNsCRHkesNoSnxE9pMf4JEUct3kYEkUihYRi3pPU0TfTuszEjz4ckXrbynOJfuzE8upFpKSfvPDsee3SeUlMLHQMBrB2sbRlNmELtUiyxfWntKLrkeK1OazgYQq96t+GuGOk6idMrbuueakhR8xWOxUN/oUCM1z2jS7dVxhaw32qYMeK75cSEUxlgKtqt2MfyjLMWg+98m0fiTA09tXzZ7skmWl0TfMA82pms66WgqN/kS58Ld0tYm35sw== sybia908@gmail.com
```

## Cara Menggunakan Deploy Key

1. Simpan kunci ini sebagai file `id_rsa` di direktori `.ssh` Anda
2. Pastikan permission file sudah benar
3. Gunakan remote SSH untuk repository GitHub

```bash
# Hapus remote yang ada
git remote remove origin

# Tambahkan remote SSH baru
git remote add origin git@github.com:sybia908/Honey-QR.git

# Coba push lagi
git push -u origin master
```

## Catatan Penting

- Deploy key ini sudah terdaftar di repository GitHub target
- Kunci ini hanya bisa digunakan untuk repository tertentu
- Jangan bagikan kunci ini dengan orang lain
