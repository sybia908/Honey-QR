@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <h2 class="mb-0">Daftar Mata Pelajaran</h2>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end">
            <a href="{{ route('subjects.create') }}" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle"></i> Tambah Mata Pelajaran
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body px-0 px-md-3">
            @if($subjects->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="mt-3">Belum ada mata pelajaran</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>SKS</th>
                                <th>Kelas</th>
                                <th>Guru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>{{ $subject->code }}</td>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->credits }}</td>
                                    <td>
                                        @foreach($subject->classes as $class)
                                            <span class="badge bg-info">{{ $class->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($subject->teachers as $teacher)
                                            <span class="badge bg-secondary">{{ $teacher->user->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-start">
                                            <a href="{{ route('subjects.show', $subject) }}" 
                                                class="btn btn-info btn-sm" 
                                                title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('subjects.edit', $subject) }}" 
                                                class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('subjects.destroy', $subject) }}" 
                                                method="POST" 
                                                class="d-inline confirm-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.confirm-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
                this.submit();
            }
        });
    });
</script>
@endpush
