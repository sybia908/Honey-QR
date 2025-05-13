#!/bin/bash

# Script untuk memperbaiki masalah duplikasi data pada aplikasi Honey QR
#
# CARA PENGGUNAAN:
# 1. Upload script ini ke server Ubuntu Anda
# 2. Jalankan perintah berikut:
#    cd /var/www/Honey-QR  (sesuaikan dengan direktori aplikasi Anda)
#    sudo chmod +x fix-duplicate.sh
#    sudo ./fix-duplicate.sh
#
# PENTING: Script ini harus dijalankan dengan sudo

echo "===== Script Perbaikan Masalah Duplikasi Data Honey QR ====="

# Tentukan variabel
APP_DIR=$(pwd)
WEB_USER="www-data"
WEB_GROUP="www-data"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")

echo "Direktori aplikasi: $APP_DIR"

# 1. Buat file PHP diagnostik untuk memperbaiki masalah duplikasi
echo "Membuat file diagnostik untuk memperbaiki masalah duplikasi..."

cat > "$APP_DIR/fix-duplicate.php" << 'EOF'
<?php

// Script untuk memperbaiki masalah duplikasi data
// Harus dijalankan dengan: php fix-duplicate.php

require __DIR__.'/vendor/autoload.php';

// Load environment variables
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== SCRIPT PERBAIKAN MASALAH DUPLIKASI DATA ===\n\n";

// 1. Periksa dan hapus duplikasi user dengan email andikabgs@gmail.com
echo "Memeriksa duplikasi user dengan email andikabgs@gmail.com...\n";

$duplicateUsers = DB::table('users')
    ->where('email', 'andikabgs@gmail.com')
    ->get();

echo "Ditemukan " . count($duplicateUsers) . " user dengan email andikabgs@gmail.com\n";

if (count($duplicateUsers) > 1) {
    // Simpan ID user pertama (yang akan dipertahankan)
    $keepUserId = $duplicateUsers[0]->id;
    
    echo "Mempertahankan user dengan ID: " . $keepUserId . "\n";
    
    // Hapus user duplikat (kecuali yang pertama)
    for ($i = 1; $i < count($duplicateUsers); $i++) {
        $deleteUserId = $duplicateUsers[$i]->id;
        echo "Menghapus user duplikat dengan ID: " . $deleteUserId . "\n";
        
        // Hapus entri terkait di tabel model_has_roles
        DB::table('model_has_roles')
            ->where('model_id', $deleteUserId)
            ->where('model_type', 'App\\Models\\User')
            ->delete();
            
        // Hapus entri terkait di tabel model_has_permissions
        DB::table('model_has_permissions')
            ->where('model_id', $deleteUserId)
            ->where('model_type', 'App\\Models\\User')
            ->delete();
            
        // Hapus user duplikat
        DB::table('users')
            ->where('id', $deleteUserId)
            ->delete();
    }
    
    echo "User duplikat telah dihapus.\n";
} else {
    echo "Tidak ada user duplikat dengan email andikabgs@gmail.com.\n";
}

// 2. Periksa apakah user afrils ada dan memiliki role admin
echo "\nMemeriksa user afrils...\n";

$afrils = User::where('username', 'afrils')->first();

if ($afrils) {
    echo "User afrils ditemukan dengan ID: " . $afrils->id . "\n";
    
    // Periksa apakah afrils memiliki role admin
    $adminRole = Role::where('name', 'admin')->first();
    
    if (!$adminRole) {
        echo "Role admin tidak ditemukan. Membuat role admin...\n";
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        echo "Role admin telah dibuat.\n";
    } else {
        echo "Role admin ditemukan dengan ID: " . $adminRole->id . "\n";
    }
    
    // Periksa apakah afrils memiliki role admin
    if (!$afrils->hasRole('admin')) {
        echo "User afrils tidak memiliki role admin. Menambahkan role admin...\n";
        $afrils->assignRole('admin');
        echo "Role admin telah ditambahkan ke user afrils.\n";
    } else {
        echo "User afrils sudah memiliki role admin.\n";
    }
    
    // Update password jika perlu
    echo "Memperbarui password user afrils...\n";
    $afrils->password = Hash::make('G4l4xymini');
    $afrils->save();
    echo "Password user afrils telah diperbarui.\n";
} else {
    echo "User afrils tidak ditemukan. Membuat user afrils...\n";
    
    // Periksa apakah role admin ada
    $adminRole = Role::where('name', 'admin')->first();
    
    if (!$adminRole) {
        echo "Role admin tidak ditemukan. Membuat role admin...\n";
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        echo "Role admin telah dibuat.\n";
    }
    
    // Buat user afrils
    $afrils = User::create([
        'name' => 'Administrator',
        'email' => 'afrils@example.com', // Gunakan email yang berbeda untuk menghindari konflik
        'username' => 'afrils',
        'password' => Hash::make('G4l4xymini'),
        'is_active' => true,
    ]);
    
    // Assign role admin
    $afrils->assignRole('admin');
    
    echo "User afrils telah dibuat dengan role admin.\n";
}

// 3. Periksa duplikasi permission
echo "\nMemeriksa duplikasi permission...\n";

$permissions = [
    'view users',
    'create users',
    'edit users',
    'delete users',
    'view roles',
    'create roles',
    'edit roles',
    'delete roles',
    'view permissions',
    'create permissions',
    'edit permissions',
    'delete permissions'
];

foreach ($permissions as $permissionName) {
    $duplicatePermissions = Permission::where('name', $permissionName)
        ->where('guard_name', 'web')
        ->get();
    
    echo "Ditemukan " . count($duplicatePermissions) . " permission dengan nama '$permissionName' untuk guard 'web'\n";
    
    if (count($duplicatePermissions) > 1) {
        // Simpan ID permission pertama (yang akan dipertahankan)
        $keepPermissionId = $duplicatePermissions[0]->id;
        
        echo "Mempertahankan permission dengan ID: " . $keepPermissionId . "\n";
        
        // Hapus permission duplikat (kecuali yang pertama)
        for ($i = 1; $i < count($duplicatePermissions); $i++) {
            $deletePermissionId = $duplicatePermissions[$i]->id;
            echo "Menghapus permission duplikat dengan ID: " . $deletePermissionId . "\n";
            
            // Hapus entri terkait di tabel role_has_permissions
            DB::table('role_has_permissions')
                ->where('permission_id', $deletePermissionId)
                ->delete();
                
            // Hapus entri terkait di tabel model_has_permissions
            DB::table('model_has_permissions')
                ->where('permission_id', $deletePermissionId)
                ->delete();
                
            // Hapus permission duplikat
            DB::table('permissions')
                ->where('id', $deletePermissionId)
                ->delete();
        }
        
        echo "Permission duplikat '$permissionName' telah dihapus.\n";
    } else if (count($duplicatePermissions) == 0) {
        // Buat permission jika belum ada
        Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
        echo "Permission '$permissionName' telah dibuat.\n";
    }
}

// 4. Pastikan role admin memiliki semua permission
echo "\nMemastikan role admin memiliki semua permission...\n";

$adminRole = Role::where('name', 'admin')->first();

if ($adminRole) {
    foreach ($permissions as $permissionName) {
        $permission = Permission::where('name', $permissionName)
            ->where('guard_name', 'web')
            ->first();
            
        if ($permission && !$adminRole->hasPermissionTo($permission)) {
            $adminRole->givePermissionTo($permission);
            echo "Permission '$permissionName' ditambahkan ke role admin.\n";
        }
    }
    
    echo "Role admin sekarang memiliki semua permission yang diperlukan.\n";
} else {
    echo "Role admin tidak ditemukan. Silakan jalankan script ini lagi.\n";
}

echo "\n=== SCRIPT PERBAIKAN SELESAI ===\n";
echo "Silakan jalankan 'php artisan cache:clear' untuk membersihkan cache.\n";

EOF

# 2. Ubah kepemilikan file
echo "Mengubah kepemilikan file fix-duplicate.php..."
chown "$WEB_USER:$WEB_GROUP" "$APP_DIR/fix-duplicate.php"
chmod 755 "$APP_DIR/fix-duplicate.php"

# 3. Jalankan script PHP
echo "Menjalankan script PHP untuk memperbaiki masalah duplikasi..."
php "$APP_DIR/fix-duplicate.php"

# 4. Bersihkan cache
echo "Membersihkan cache Laravel..."
php "$APP_DIR/artisan" cache:clear
php "$APP_DIR/artisan" config:clear
php "$APP_DIR/artisan" route:clear
php "$APP_DIR/artisan" view:clear
php "$APP_DIR/artisan" optimize:clear

# 5. Restart layanan web server
echo "Merestart layanan web server..."
systemctl restart php*-fpm
systemctl restart nginx

echo "===== Perbaikan Masalah Duplikasi Data Selesai ====="
echo "Masalah duplikasi data telah diperbaiki."
echo "Coba akses aplikasi lagi untuk melihat apakah masalah sudah teratasi."
