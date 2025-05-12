@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Detail Guru</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($teacher->user->avatar)
                        <img src="{{ asset($teacher->user->avatar) }}" 
                            alt="{{ $teacher->user->name }}" 
                            class="rounded-circle mb-3"
                            width="150" height="150">
                    @else
                        <div class="display-1 text-muted mb-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                    <h5 class="card-title">{{ $teacher->user->name }}</h5>
                    <p class="text-muted mb-1">{{ $teacher->nip }}</p>
                    <p class="text-muted mb-0">{{ $teacher->position ?? 'Guru' }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Login</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8">{{ $teacher->user->username }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $teacher->user->email }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $teacher->user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $teacher->user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Pribadi</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Jenis Kelamin</dt>
                        <dd class="col-sm-9">{{ $teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>

                        <dt class="col-sm-3">Tanggal Lahir</dt>
                        <dd class="col-sm-9">{{ $teacher->birth_date ? $teacher->birth_date->format('d/m/Y') : '-' }}</dd>

                        <dt class="col-sm-3">No. Telepon</dt>
                        <dd class="col-sm-9">{{ $teacher->phone ?? '-' }}</dd>

                        <dt class="col-sm-3">Alamat</dt>
                        <dd class="col-sm-9">{{ $teacher->address ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            @if($teacher->homeRoomClasses->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Wali Kelas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacher->homeRoomClasses as $class)
                                <tr>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->students_count }} siswa</td>
                                    <td>
                                        <a href="{{ route('classes.show', $class) }}" 
                                            class="btn btn-info btn-sm">
                                            <i class="bi bi-eye"></i> Lihat Kelas
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Riwayat Absensi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->attendances()->latest()->take(10)->get() as $attendance)
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
                                    <td colspan="4" class="text-center">Tidak ada data absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div>
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('teachers.destroy', $teacher) }}" 
                        method="POST" 
                        class="d-inline confirm-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
