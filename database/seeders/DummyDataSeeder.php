<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\QRCode;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Mulai generate data dummy...');

        // Pastikan role sudah ada
        $roles = ['admin', 'guru', 'siswa'];
        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role, 'guard_name' => 'web']);
                $this->command->info("Role {$role} dibuat.");
            }
        }

        // Buat guru
        $this->command->info('Membuat data guru...');
        $teachers = Teacher::factory()->count(10)->create();
        $this->command->info('10 data guru berhasil dibuat');

        // Buat kelas
        $this->command->info('Membuat data kelas...');
        $classes = ClassRoom::factory()->count(12)->create();
        $this->command->info('12 data kelas berhasil dibuat');

        // Buat mata pelajaran
        $this->command->info('Membuat data mata pelajaran...');
        $subjects = Subject::factory()->count(15)->create();
        $this->command->info('15 data mata pelajaran berhasil dibuat');

        // Buat siswa
        $this->command->info('Membuat data siswa...');
        $students = Student::factory()->count(50)->create();
        $this->command->info('50 data siswa berhasil dibuat');

        // Buat QR Code
        $this->command->info('Membuat data QR Code...');
        $qrcodes = QRCode::factory()->count(25)->create();
        $this->command->info('25 data QR Code berhasil dibuat');

        // Buat data absensi
        $this->command->info('Membuat data absensi...');
        $attendances = Attendance::factory()->count(200)->create();
        $this->command->info('200 data absensi berhasil dibuat');

        // Hubungkan guru dengan mata pelajaran
        $this->command->info('Menghubungkan guru dengan mata pelajaran...');
        foreach ($teachers as $teacher) {
            $teacher->subjects()->attach(
                $subjects->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

        // Hubungkan kelas dengan mata pelajaran
        $this->command->info('Menghubungkan kelas dengan mata pelajaran...');
        foreach ($classes as $class) {
            $class->subjects()->attach(
                $subjects->random(rand(5, 10))->pluck('id')->toArray()
            );
        }

        $this->command->info('Selesai! Data dummy telah dibuat dengan sukses.');
    }
}
