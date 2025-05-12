<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Lokasi Masuk',
            'Lokasi Keluar',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date,
            $attendance->user->student->nis ?? '-',
            $attendance->user->name,
            $attendance->user->student->class->name ?? '-',
            $attendance->time_in,
            $attendance->time_out ?? '-',
            ucfirst($attendance->status),
            sprintf('%.6f, %.6f', $attendance->latitude_in, $attendance->longitude_in),
            $attendance->time_out ? sprintf('%.6f, %.6f', $attendance->latitude_out, $attendance->longitude_out) : '-',
        ];
    }
}
