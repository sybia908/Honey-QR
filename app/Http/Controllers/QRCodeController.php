<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\QRCode;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QRCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin,guru'])->except(['scan', 'verify']);
    }

    public function index()
    {
        $qrcodes = QRCode::with(['creator', 'class', 'subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('qrcodes.index', compact('qrcodes'));
    }

    public function create()
    {
        $classes = ClassRoom::where('is_active', true)->get();
        $subjects = Subject::all();
        return view('qrcodes.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'valid_until' => 'required|date|after:now',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Cari jadwal dari tabel pivot class_subject
        $schedule = DB::table('class_subject')
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        // Jika jadwal tidak ditemukan
        if (!$schedule) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['schedule' => 'Jadwal untuk kelas dan mata pelajaran ini belum tersedia. Silakan atur jadwal terlebih dahulu.']);
        }
        
        $code = uniqid('QR-');
        $validUntil = Carbon::parse($request->valid_until);

        $qrcode = QRCode::create([
            'code' => $code,
            'created_by' => auth()->id(),
            'valid_until' => $validUntil,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'active_from' => $schedule->start_time,
            'active_until' => $schedule->end_time,
        ]);

        // Generate QR Code image
        $qrImage = QrCodeGenerator::size(300)
            ->format('svg')
            ->generate(route('qrcodes.verify', $qrcode->code));

        return view('qrcodes.show', compact('qrcode', 'qrImage'));
    }

    public function show(QRCode $qrcode)
    {
        $qrImage = QrCodeGenerator::size(300)
            ->format('svg')
            ->generate(route('qrcodes.verify', $qrcode->code));

        return view('qrcodes.show', compact('qrcode', 'qrImage'));
    }

    public function scan()
    {
        return view('qrcodes.scan');
    }

    public function verify($code)
    {
        $qrcode = QRCode::with(['class', 'subject'])->where('code', $code)->first();

        if (!$qrcode) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid.',
            ], 404);
        }

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

        return response()->json([
            'status' => 'success',
            'qrcode' => $qrcode,
        ]);
    }

    public function destroy(QRCode $qrcode)
    {
        // Gunakan query builder untuk mengecek relasi secara manual
        $hasAttendances = DB::table('attendances')
            ->where('qrcode_id', $qrcode->id)
            ->exists();

        if ($hasAttendances) {
            return back()->with('error', 'Tidak dapat menghapus QR Code yang sudah digunakan untuk absensi.');
        }

        $qrcode->delete();

        return redirect()->route('qrcodes.index')
            ->with('success', 'QR Code berhasil dihapus.');
    }
}
