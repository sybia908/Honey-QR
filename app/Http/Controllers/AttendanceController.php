<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QRCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendancesExport;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin,guru'])->except(['index', 'store', 'checkIn', 'checkOut']);
    }

    public function index(Request $request)
    {
        $query = Attendance::with(['user', 'qrcode'])
            ->when($request->date, function ($q) use ($request) {
                return $q->whereDate('date', $request->date);
            })
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            });

        if (auth()->user()->hasRole('guru')) {
            $query->whereHas('user', function ($q) {
                $q->whereHas('student', function ($q) {
                    $q->whereHas('class', function ($q) {
                        $q->where('homeroom_teacher_id', auth()->id());
                    });
                });
            });
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->paginate(10);

        return view('attendances.index', compact('attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'qrcode' => 'required|exists:qrcodes,code',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $qrcode = QRCode::where('code', $request->qrcode)->first();

        if (Carbon::now()->isAfter($qrcode->valid_until)) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code sudah kadaluarsa.',
            ], 400);
        }

        // Check if QR code is active based on time
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');

        if ($qrcode->active_from && $qrcode->active_until) {
            $activeFrom = Carbon::createFromFormat('H:i', $qrcode->active_from)->format('H:i:s');
            $activeUntil = Carbon::createFromFormat('H:i', $qrcode->active_until)->format('H:i:s');

            if ($currentTime < $activeFrom || $currentTime > $activeUntil) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'QR Code hanya aktif dari jam ' . $qrcode->active_from . ' sampai ' . $qrcode->active_until,
                ], 400);
            }
        }

        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        $existingAttendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            if ($existingAttendance->time_out) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan absensi masuk dan keluar hari ini.',
                ], 400);
            }

            // Check out
            $existingAttendance->update([
                'time_out' => $now->format('H:i:s'),
                'longitude_out' => $request->longitude,
                'latitude_out' => $request->latitude,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil melakukan absensi keluar.',
                'attendance' => $existingAttendance,
            ]);
        }

        // Check in
        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'qrcode_id' => $qrcode->id,
            'date' => $today,
            'time_in' => $now->format('H:i:s'),
            'status' => $this->determineStatus($now->format('H:i:s')),
            'latitude_in' => $request->latitude,
            'longitude_in' => $request->longitude,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil melakukan absensi masuk.',
            'attendance' => $attendance,
        ]);
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['user', 'qrcode']);
        return view('attendances.show', compact('attendance'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $query = Attendance::with(['user', 'qrcode'])
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if (auth()->user()->hasRole('guru')) {
            $query->whereHas('user', function ($q) {
                $q->whereHas('student', function ($q) {
                    $q->whereHas('class', function ($q) {
                        $q->where('homeroom_teacher_id', auth()->id());
                    });
                });
            });
        }

        $attendances = $query->get();

        return Excel::download(new AttendancesExport($attendances), 'attendances.xlsx');
    }

    protected function determineStatus($timeIn)
    {
        $time = Carbon::createFromFormat('H:i:s', $timeIn);
        $lateThreshold = Carbon::createFromFormat('H:i', '07:15');

        return $time->isAfter($lateThreshold) ? 'late' : 'on_time';
    }
}
