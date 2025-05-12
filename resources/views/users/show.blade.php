@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Detail User</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/avatars/'.$user->avatar) }}" 
                            alt="{{ $user->name }}" 
                            class="rounded-circle mb-3"
                            width="150" height="150">
                    @else
                        <div class="display-1 text-muted mb-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                    <h5 class="card-title">{{ $user->name }}</h5>
                    @foreach($user->roles as $role)
                        <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Login</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8">{{ $user->username }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Dibuat</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Diperbarui</dt>
                        <dd class="col-sm-8">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @if($user->student)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Siswa</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">NIS</dt>
                        <dd class="col-sm-9">{{ $user->student->nis }}</dd>

                        <dt class="col-sm-3">Kelas</dt>
                        <dd class="col-sm-9">{{ $user->student->class->name }}</dd>

                        <dt class="col-sm-3">Jenis Kelamin</dt>
                        <dd class="col-sm-9">{{ $user->student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>

                        <dt class="col-sm-3">Tanggal Lahir</dt>
                        <dd class="col-sm-9">{{ $user->student->birth_date ? $user->student->birth_date->format('d/m/Y') : '-' }}</dd>

                        <dt class="col-sm-3">No. Telepon</dt>
                        <dd class="col-sm-9">{{ $user->student->phone ?? '-' }}</dd>

                        <dt class="col-sm-3">Alamat</dt>
                        <dd class="col-sm-9">{{ $user->student->address ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
            @endif

            @if($user->teacher)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Guru</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">NIP</dt>
                        <dd class="col-sm-9">{{ $user->teacher->nip }}</dd>

                        <dt class="col-sm-3">Jabatan</dt>
                        <dd class="col-sm-9">{{ $user->teacher->position ?? '-' }}</dd>

                        <dt class="col-sm-3">Jenis Kelamin</dt>
                        <dd class="col-sm-9">{{ $user->teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>

                        <dt class="col-sm-3">Tanggal Lahir</dt>
                        <dd class="col-sm-9">{{ $user->teacher->birth_date ? $user->teacher->birth_date->format('d/m/Y') : '-' }}</dd>

                        <dt class="col-sm-3">No. Telepon</dt>
                        <dd class="col-sm-9">{{ $user->teacher->phone ?? '-' }}</dd>

                        <dt class="col-sm-3">Alamat</dt>
                        <dd class="col-sm-9">{{ $user->teacher->address ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            @if($user->teacher->homeRoomClasses->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Wali Kelas</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($user->teacher->homeRoomClasses as $class)
                        <li class="list-group-item">{{ $class->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
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
                                @forelse($user->attendances()->latest()->take(5)->get() as $attendance)
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
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" 
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
