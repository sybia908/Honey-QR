<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QRCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $user = $request->user();
        $student = $user->student;

        // Verify if student has this subject
        $subject = $student->class->subjects()
            ->where('subjects.id', $request->subject_id)
            ->first();

        if (!$subject) {
            throw ValidationException::withMessages([
                'subject_id' => ['Mata pelajaran tidak ditemukan dalam jadwal kelas Anda.'],
            ]);
        }

        // Check if already attended
        $existingAttendance = Attendance::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($existingAttendance) {
            throw ValidationException::withMessages([
                'qr_code' => ['Anda sudah melakukan absensi untuk mata pelajaran ini hari ini.'],
            ]);
        }

        // Verify QR Code
        $qrCode = QRCode::where('code', $request->qr_code)
            ->where('valid_until', '>', now())
            ->first();

        if (!$qrCode) {
            throw ValidationException::withMessages([
                'qr_code' => ['QR Code tidak valid atau sudah kadaluarsa.'],
            ]);
        }

        // Create attendance
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'qrcode_id' => $qrCode->id,
            'status' => 'hadir',
            'recorded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Absensi berhasil dicatat.',
            'attendance' => [
                'id' => $attendance->id,
                'status' => $attendance->status,
                'recorded_at' => $attendance->recorded_at,
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                ],
            ],
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $student = $user->student;

        $attendances = $student->attendances()
            ->with('subject')
            ->latest()
            ->paginate(20);

        return response()->json([
            'attendances' => $attendances->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'status' => $attendance->status,
                    'recorded_at' => $attendance->recorded_at,
                    'subject' => [
                        'id' => $attendance->subject->id,
                        'name' => $attendance->subject->name,
                        'code' => $attendance->subject->code,
                    ],
                ];
            }),
            'pagination' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
            ],
        ]);
    }
}
