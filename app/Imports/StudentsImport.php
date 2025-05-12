<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::beginTransaction();

            try {
                $user = User::create([
                    'name' => $row['nama'],
                    'email' => $row['email'],
                    'username' => $row['username'],
                    'password' => Hash::make($row['password']),
                    'is_active' => true,
                ]);

                $user->assignRole('siswa');

                Student::create([
                    'nis' => $row['nis'],
                    'user_id' => $user->id,
                    'class_id' => $row['kelas_id'],
                    'phone' => $row['no_telepon'],
                    'address' => $row['alamat'],
                    'birth_date' => $row['tanggal_lahir'],
                    'gender' => $row['jenis_kelamin'],
                    'is_active' => true,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|string|max:255|unique:students',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'kelas_id' => 'required|exists:classes,id',
            'no_telepon' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
