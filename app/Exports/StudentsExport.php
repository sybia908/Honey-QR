<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    private $isTemplate;

    public function __construct($isTemplate = false)
    {
        $this->isTemplate = $isTemplate;
    }

    public function collection()
    {
        if ($this->isTemplate) {
            return collect([]);
        }

        return Student::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama',
            'Email',
            'Username',
            'Password',
            'Kelas ID',
            'No. Telepon',
            'Alamat',
            'Tanggal Lahir',
            'Jenis Kelamin',
        ];
    }

    public function map($student): array
    {
        if ($this->isTemplate) {
            return [];
        }

        return [
            $student->nis,
            $student->user->name,
            $student->user->email,
            $student->user->username,
            '', // Password kosong untuk export
            $student->class_id,
            $student->phone,
            $student->address,
            $student->birth_date,
            $student->gender,
        ];
    }
}
