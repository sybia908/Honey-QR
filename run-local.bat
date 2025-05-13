@echo off
echo === Menjalankan Aplikasi Honey QR di Lingkungan Lokal Windows ===

REM Tentukan variabel
set APP_DIR=%cd%
set PORT=8000

echo Direktori aplikasi: %APP_DIR%

REM Periksa apakah composer sudah terinstall
where composer >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer tidak ditemukan. Silakan install Composer terlebih dahulu.
    echo https://getcomposer.org/download/
    exit /b 1
)

REM Periksa apakah file .env sudah ada
if not exist "%APP_DIR%\.env" (
    echo Membuat file .env dari .env.example...
    copy "%APP_DIR%\.env.example" "%APP_DIR%\.env"
    
    REM Generate application key
    echo Generating application key...
    php artisan key:generate
)

REM Install dependencies
echo Menginstall dependencies...
composer install

REM Jalankan migrasi database (opsional)
echo.
set /p run_migration="Apakah Anda ingin menjalankan migrasi database? (y/n): "
if /i "%run_migration%"=="y" (
    echo Menjalankan migrasi database...
    php artisan migrate:fresh
    
    echo.
    set /p run_seed="Apakah Anda ingin mengisi database dengan data awal? (y/n): "
    if /i "%run_seed%"=="y" (
        echo Mengisi database dengan data awal...
        php artisan db:seed --class=RoleAndPermissionSeeder
        php artisan db:seed --class=AdminUserSeeder
    )
)

REM Bersihkan cache
echo Membersihkan cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

REM Jalankan aplikasi
echo.
echo Menjalankan aplikasi pada port %PORT%...
echo Akses aplikasi di http://localhost:%PORT%
echo.
echo Tekan Ctrl+C untuk menghentikan server
echo.
php artisan serve --port=%PORT%
