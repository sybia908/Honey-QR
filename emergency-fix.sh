#!/bin/bash

# Script darurat untuk memperbaiki masalah view pada aplikasi Honey QR
# yang sudah di-deploy di server Ubuntu
#
# CARA PENGGUNAAN:
# 1. Upload script ini ke server Ubuntu Anda
# 2. Jalankan perintah berikut:
#    cd /var/www/Honey-QR  (sesuaikan dengan direktori aplikasi Anda)
#    sudo chmod +x emergency-fix.sh
#    sudo ./emergency-fix.sh
#
# PENTING: Script ini harus dijalankan dengan sudo

echo "===== Script Perbaikan Darurat Honey QR ====="
echo "Versi 1.0 - Perbaikan Langsung File Compiled View"

# 1. Hapus semua file cache view
echo "Menghapus semua file cache view..."
rm -rf storage/framework/views/*

# 2. Perbaiki file student.blade.php
echo "Memperbaiki file student.blade.php..."

# Buat file student.blade.php yang baru
cat > resources/views/dashboard/student.blade.php << 'EOF'
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

# Perbaiki izin file
chown www-data:www-data resources/views/dashboard/student.blade.php
chmod 644 resources/views/dashboard/student.blade.php

# 3. Hapus cache aplikasi
echo "Membersihkan cache aplikasi..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 4. Perbaiki izin storage
echo "Memperbaiki izin storage..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Restart PHP-FPM dan Nginx
echo "Merestart layanan..."
systemctl restart php*-fpm
systemctl restart nginx

# 6. Buat file diagnostik untuk memeriksa user
echo "Membuat file diagnostik..."
cat > public/check-user.php << 'EOF'
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h1>Diagnostik User</h1>";
echo "<pre>";

try {
    $user = \App\Models\User::where('username', 'afrils')->first();
    
    if ($user) {
        echo "User ditemukan:\n";
        echo "ID: " . $user->id . "\n";
        echo "Nama: " . $user->name . "\n";
        echo "Username: " . $user->username . "\n";
        echo "Email: " . $user->email . "\n";
        echo "Is Active: " . ($user->is_active ? 'Ya' : 'Tidak') . "\n\n";
        
        echo "Roles:\n";
        foreach ($user->roles as $role) {
            echo "- " . $role->name . "\n";
        }
        
        echo "\nStudent:\n";
        if ($user->student) {
            echo "NIS: " . $user->student->nis . "\n";
            echo "Class ID: " . $user->student->class_id . "\n";
            
            if ($user->student->class) {
                echo "Class Name: " . $user->student->class->name . "\n";
            } else {
                echo "Class: Tidak ditemukan\n";
            }
        } else {
            echo "Tidak memiliki data student\n";
        }
    } else {
        echo "User dengan username 'afrils' tidak ditemukan.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

echo "</pre>";
echo "<p>File ini hanya untuk diagnostik. Hapus setelah selesai digunakan.</p>";
EOF

chown www-data:www-data public/check-user.php
chmod 644 public/check-user.php

echo "===== Perbaikan Selesai ====="
echo "Coba login dengan kredensial berikut:"
echo "Username: afrils"
echo "Password: G4l4xymini"
echo ""
echo "Jika masih ada masalah, cek file diagnostik di:"
echo "http://220.247.171.222:2200/check-user.php"
echo ""
echo "PENTING: Hapus file diagnostik setelah selesai digunakan dengan perintah:"
echo "sudo rm /var/www/Honey-QR/public/check-user.php"
