@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Manajemen Kelas</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('classes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kelas
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
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span>Total: <strong>{{ $classes->total() }}</strong> kelas</span>
            <div class="text-muted">{{ $classes->firstItem() ?? 0 }}-{{ $classes->lastItem() ?? 0 }} dari {{ $classes->total() }}</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Wali Kelas</th>
                            <th>Jumlah Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->name }}</td>
                            <td>
                                @if($class->homeroomTeacher && $class->homeroomTeacher->user)
                                    {{ $class->homeroomTeacher->user->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge bg-info rounded-pill">{{ $class->students_count }}</span></td>
                            <td>
                                <a href="{{ route('classes.show', $class) }}" 
                                    class="btn btn-info btn-sm"
                                    title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('classes.edit', $class) }}" 
                                    class="btn btn-warning btn-sm"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('classes.destroy', $class) }}" 
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
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
