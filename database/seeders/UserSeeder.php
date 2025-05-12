<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create Teachers
        $teachers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'username' => 'budi',
                'nip' => '198501012010011001',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'username' => 'siti',
                'nip' => '198601022010012002',
            ],
        ];

        foreach ($teachers as $teacher) {
            $user = User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'username' => $teacher['username'],
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);
            $user->assignRole('guru');

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $teacher['nip'],
            ]);
        }

        // Create Students
        $classes = \App\Models\Classes::all();
        $studentCounter = 1;

        foreach ($classes as $class) {
            for ($i = 1; $i <= 5; $i++) {
                $nis = '2025' . str_pad($studentCounter, 3, '0', STR_PAD_LEFT);
                $username = 'siswa' . $studentCounter;
                
                $user = User::create([
                    'name' => 'Siswa ' . $studentCounter,
                    'email' => $username . '@example.com',
                    'username' => $username,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]);
                $user->assignRole('siswa');

                Student::create([
                    'user_id' => $user->id,
                    'class_id' => $class->id,
                    'nis' => $nis,
                ]);

                $studentCounter++;
            }
        }
    }
}
