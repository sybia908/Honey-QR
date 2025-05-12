<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
            ->where('is_active', true)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        if (! $user->hasRole('siswa')) {
            throw ValidationException::withMessages([
                'username' => ['Akun ini bukan akun siswa.'],
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset('storage/avatars/' . $user->avatar) : null,
                'student' => $user->student,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $user->load('student.class', 'roles');

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset('storage/avatars/' . $user->avatar) : null,
                'student' => $user->student,
                'roles' => $user->roles->pluck('name'),
            ],
        ]);
    }

    public function dashboardStats(Request $request)
    {
        // Import model yang diperlukan
        $user = $request->user();
        $user->load('roles');
        
        // Ambil statistik dashboard
        $stats = [
            'total_classes' => \App\Models\ClassRoom::count(),
            'total_students' => \App\Models\Student::count(),
            'total_teachers' => \App\Models\Teacher::count(),
            'total_attendances' => \App\Models\Attendance::whereDate('created_at', \Carbon\Carbon::today())->count(),
        ];
        
        // Ambil absensi terbaru
        $recentAttendances = \App\Models\Attendance::with(['user', 'student', 'student.class'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date->format('Y-m-d'),
                    'user' => $attendance->user->name,
                    'className' => optional(optional($attendance->student)->class)->name ?? '-',
                    'status' => $attendance->status,
                ];
            });
        
        // Ambil QR Code aktif
        $activeQRCodes = \App\Models\QRCode::with(['creator'])
            ->where('valid_until', '>', now())
            ->latest()
            ->take(5)
            ->get()
            ->map(function($qrcode) {
                return [
                    'id' => $qrcode->id,
                    'code' => $qrcode->code,
                    'validUntil' => $qrcode->valid_until->format('Y-m-d'),
                    'creator' => $qrcode->creator->name,
                ];
            });
        
        return response()->json([
            'status' => 'success',
            'stats' => $stats,
            'attendances' => $recentAttendances,
            'qrcodes' => $activeQRCodes,
        ]);
    }
}
