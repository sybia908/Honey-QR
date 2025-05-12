@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Detail Kelas</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kelas</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama Kelas</dt>
                        <dd class="col-sm-8">{{ $class->name }}</dd>

                        <dt class="col-sm-4">Wali Kelas</dt>
                        <dd class="col-sm-8">
                            @if($class->homeRoomTeacher)
                                {{ $class->homeRoomTeacher->user->name }}
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Jumlah Siswa</dt>
                        <dd class="col-sm-8">{{ $class->students_count }} siswa</dd>
                    </dl>
                </div>
            </div>

            @if($class->homeRoomTeacher)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Wali Kelas</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">NIP</dt>
                        <dd class="col-sm-8">{{ $class->homeRoomTeacher->nip }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $class->homeRoomTeacher->user->email }}</dd>

                        <dt class="col-sm-4">No. Telepon</dt>
                        <dd class="col-sm-8">{{ $class->homeRoomTeacher->phone ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Siswa</h5>
                    <a href="{{ route('students.create', ['class_id' => $class->id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Siswa
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->students as $student)
                                <tr>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        <span class="badge {{ $student->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student) }}" 
                                            class="btn btn-info btn-sm"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" 
                                            class="btn btn-warning btn-sm"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada siswa</td>
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
                <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div>
                    <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('classes.destroy', $class) }}" 
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
