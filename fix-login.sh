#!/bin/bash

# Script untuk memperbaiki masalah login di aplikasi Honey QR
# yang sudah di-deploy di server Ubuntu

echo "===== Script Perbaikan Login Honey QR ====="
echo "Versi 3.0 - Perbaikan Error Dashboard Student"

# 1. Pastikan konfigurasi .env sudah benar
echo "Memeriksa konfigurasi .env..."
if [ ! -f .env ]; then
    echo "File .env tidak ditemukan. Menyalin dari .env.ubuntu..."
    cp .env.ubuntu .env
fi

# Pastikan APP_URL benar
sed -i "s|APP_URL=http://your-domain.com|APP_URL=http://220.247.171.222:2200|g" .env

# 2. Generate application key jika belum ada
echo "Memeriksa dan menghasilkan application key..."
php artisan key:generate --force

# 3. Bersihkan cache konfigurasi
echo "Membersihkan cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# 4. Periksa apakah database sudah memiliki data
echo "Memeriksa database..."
USER_EXISTS=$(php artisan tinker --execute="echo \App\Models\User::where('username', 'afrils')->exists() ? 'true' : 'false';")

if [ "$USER_EXISTS" = "true" ]; then
    echo "User admin sudah ada di database. Memastikan user memiliki role admin..."
    
    # Pastikan role admin ada
    ROLE_EXISTS=$(php artisan tinker --execute="echo \Spatie\Permission\Models\Role::where('name', 'admin')->exists() ? 'true' : 'false';")
    
    if [ "$ROLE_EXISTS" = "false" ]; then
        echo "Role admin tidak ditemukan. Membuat role admin..."
        php artisan tinker --execute="\Spatie\Permission\Models\Role::create(['name' => 'admin']);" > /dev/null 2>&1
    fi
    
    # Assign role admin ke user afrils
    echo "Mengassign role admin ke user afrils..."
    php artisan tinker --execute="\App\Models\User::where('username', 'afrils')->first()->assignRole('admin');" > /dev/null 2>&1
    
    # Pastikan user aktif
    echo "Memastikan user aktif..."
    php artisan tinker --execute="\App\Models\User::where('username', 'afrils')->update(['is_active' => true]);" > /dev/null 2>&1
    
    # Reset password jika diperlukan
    echo "Mereset password user admin..."
    php artisan tinker --execute="\App\Models\User::where('username', 'afrils')->update(['password' => bcrypt('G4l4xymini')]);" > /dev/null 2>&1
else
    # Database kosong atau user tidak ada, lakukan migrasi dan seeding
    echo "User admin tidak ditemukan. Melakukan migrasi dan seeding..."
    
    # Jalankan migrasi (tanpa fresh untuk menghindari menghapus data yang mungkin penting)
    echo "Menjalankan migrasi database..."
    php artisan migrate --force
    
    # Coba jalankan seeder roles dan permissions dengan --class untuk menghindari duplikasi
    echo "Membuat roles dan permissions..."
    php artisan db:seed --class=RoleAndPermissionSeeder --force || true
    
    # Buat user admin secara manual untuk menghindari duplikasi
    echo "Membuat user admin secara manual..."
    php artisan tinker --execute="
    try {
        \$user = \App\Models\User::firstOrCreate(
            ['username' => 'afrils'],
            [
                'name' => 'Administrator',
                'email' => 'andikabgs@gmail.com',
                'password' => bcrypt('G4l4xymini'),
                'is_active' => true
            ]
        );
        
        if (\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            \$user->assignRole('admin');
            echo 'User admin berhasil dibuat dan diberi role admin.';
        } else {
            \$role = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
            \$user->assignRole('admin');
            echo 'User admin dan role admin berhasil dibuat.';
        }
    } catch (\Exception \$e) {
        echo 'Error: ' . \$e->getMessage();
    }
    " || true
fi

# 7. Periksa dan perbaiki izin storage dan bootstrap/cache
echo "Memeriksa izin direktori storage dan bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Perbaiki masalah session
echo "Memperbaiki masalah session..."
mkdir -p storage/framework/sessions
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions

# 8.1 Perbaiki masalah view student dashboard
echo "Memperbaiki masalah view student dashboard..."
cat > resources/views/dashboard/student.blade.php.new << 'EOF'
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="h3 mb-0">Dashboard Siswa</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" 
                            alt="{{ auth()->user()->name }}" 
                            class="rounded-circle mb-3 shadow"
                            width="128" height="128"
                            style="object-fit: cover;">
                    @else
                        <div class="display-1 text-muted mb-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                    <h5 class="card-title fw-bold">{{ auth()->user()->name }}</h5>
                    @if(auth()->user()->student)
                    <p class="card-text text-muted">
                        <span class="d-block">NIS: {{ auth()->user()->student->nis }}</span>
                        <span class="d-block">Kelas: {{ auth()->user()->student->class->name ?? 'Tidak ada kelas' }}</span>
                    </p>
                    @else
                    <p class="card-text text-muted">
                        <span class="d-block">Role: {{ auth()->user()->roles->first()->name ?? 'Tidak ada role' }}</span>
                    </p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Scan QR Code</h5>
                    <p class="card-text text-muted">Silakan scan QR Code untuk melakukan absensi</p>
                    <a href="{{ route('qrcodes.scan') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Scan QR Code</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">Riwayat Absensi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(App\Models\Attendance::where('user_id', auth()->id())->latest()->take(10)->get() as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->time_in }}</td>
                                    <td>{{ $attendance->time_out ?? '-' }}</td>
                                    <td>
                                        @if($attendance->status === 'on_time')
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        @else
                                            <span class="badge bg-warning">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOF

# Ganti file lama dengan file baru
mv resources/views/dashboard/student.blade.php.new resources/views/dashboard/student.blade.php
chown www-data:www-data resources/views/dashboard/student.blade.php
chmod 644 resources/views/dashboard/student.blade.php

# 9. Regenerasi cache konfigurasi
echo "Mengoptimalkan aplikasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 10. Restart layanan web server dan PHP-FPM
echo "Merestart layanan..."
systemctl restart php*-fpm
systemctl restart nginx

echo "===== Perbaikan Selesai ====="
echo "Coba login dengan kredensial berikut:"
echo "Username: afrils"
echo "Password: G4l4xymini"
echo ""
echo "Jika masih tidak bisa login, jalankan perintah berikut untuk melihat log:"
echo "tail -f storage/logs/laravel.log"
echo ""
echo "Jika masih mendapatkan error 419 page expired, coba jalankan perintah berikut:"
echo "php artisan session:table"
echo "php artisan migrate"
echo "php artisan config:clear"
echo "php artisan cache:clear"
echo "systemctl restart php*-fpm"
echo "systemctl restart nginx"
