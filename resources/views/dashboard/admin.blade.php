@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="h3 mb-0">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Kelas</h6>
                            <h2 class="display-6 mb-0">{{ $total_classes }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-primary rounded-circle shadow-sm p-2">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary-dark">
                    <a href="{{ route('classes.index') }}" class="text-white text-decoration-none d-flex align-items-center justify-content-between">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Siswa</h6>
                            <h2 class="display-6 mb-0">{{ $total_students }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-success rounded-circle shadow-sm p-2">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success-dark">
                    <a href="{{ route('students.index') }}" class="text-white text-decoration-none d-flex align-items-center justify-content-between">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Guru</h6>
                            <h2 class="display-6 mb-0">{{ $total_teachers }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-info rounded-circle shadow-sm p-2">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info-dark">
                    <a href="{{ route('teachers.index') }}" class="text-white text-decoration-none d-flex align-items-center justify-content-between">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Absensi Hari Ini</h6>
                            <h2 class="display-6 mb-0">{{ $total_attendances }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-warning rounded-circle shadow-sm p-2">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-warning-dark">
                    <a href="{{ route('attendances.index') }}" class="text-white text-decoration-none d-flex align-items-center justify-content-between">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">Absensi Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(App\Models\Attendance::with(['user.student.class'])->latest()->take(5)->get() as $attendance)
                                <tr>
                                    <td>{{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ optional(optional($attendance->user->student)->class)->name ?? '-' }}</td>
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
                                    <td colspan="4" class="text-center">Belum ada data absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">QR Code Aktif</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse(App\Models\QRCode::where('valid_until', '>', now())->latest()->take(5)->get() as $qrcode)
                        <a href="{{ route('qrcodes.show', $qrcode) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $qrcode->code }}</h6>
                                <small>{{ $qrcode->valid_until->diffForHumans() }}</small>
                            </div>
                            <small>Dibuat oleh: {{ $qrcode->creator->name }}</small>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            Belum ada QR Code aktif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
