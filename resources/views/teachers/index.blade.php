@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Manajemen Guru</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Guru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('teachers.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" 
                            class="form-control" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama/NIP...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Wali Kelas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->nip }}</td>
                            <td>{{ $teacher->user->name }}</td>
                            <td>{{ $teacher->position ?? '-' }}</td>
                            <td>
                                @if($teacher->homeRoomClasses->isNotEmpty())
                                    @foreach($teacher->homeRoomClasses as $class)
                                        <span class="badge bg-info">{{ $class->name }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $teacher->user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $teacher->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('teachers.show', $teacher) }}" 
                                    class="btn btn-info btn-sm"
                                    title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('teachers.edit', $teacher) }}" 
                                    class="btn btn-warning btn-sm"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" 
                                    method="POST" 
                                    class="d-inline confirm-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="btn btn-danger btn-sm"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
