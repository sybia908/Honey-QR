@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="h3 mb-0">Dashboard Siswa</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" 
                            alt="{{ auth()->user()->name }}" 
                            class="rounded-circle mb-3 shadow"
                            width="128" height="128"
                            style="object-fit: cover;">
                    @else
                        <div class="display-1 text-muted mb-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                    <h5 class="card-title fw-bold">{{ auth()->user()->name }}</h5>
                    <p class="card-text text-muted">
                        <span class="d-block">NIS: {{ auth()->user()->student->nis }}</span>
                        <span class="d-block">Kelas: {{ auth()->user()->student->class->name }}</span>
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Scan QR Code</h5>
                    <p class="card-text text-muted">Silakan scan QR Code untuk melakukan absensi</p>
                    <a href="{{ route('qrcodes.scan') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Scan QR Code</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">Riwayat Absensi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(App\Models\Attendance::where('user_id', auth()->id())->latest()->take(10)->get() as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->time_in }}</td>
                                    <td>{{ $attendance->time_out ?? '-' }}</td>
                                    <td>
                                        @if($attendance->status === 'on_time')
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        @else
                                            <span class="badge bg-warning">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
