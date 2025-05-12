@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <h2 class="mb-0">Detail Mata Pelajaran</h2>
        </div>
        <div class="col-12 col-md-6">
            <div class="d-flex gap-2 justify-content-start justify-content-md-end">
                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">Informasi Mata Pelajaran</h5>
                    <table class="table">
                        <tr>
                            <th width="30%">Kode</th>
                            <td>{{ $subject->code }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $subject->name }}</td>
                        </tr>
                        <tr>
                            <th>SKS</th>
                            <td>{{ $subject->credits }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $subject->description ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">Guru Pengajar</h5>
                    @if($subject->teachers->isEmpty())
                        <p class="text-muted">Belum ada guru pengajar</p>
                    @else
                        <div class="list-group">
                            @foreach($subject->teachers as $teacher)
                                <div class="list-group-item">
                                    <div class="d-flex flex-column flex-md-row w-100 justify-content-between align-items-start align-items-md-center gap-2">
                                        <div>
                                            <h6 class="mb-1">{{ $teacher->user->name }}</h6>
                                            <small class="text-muted d-block">{{ $teacher->user->email }}</small>
                                        </div>
                                        <span class="badge bg-secondary">NIP: {{ $teacher->nip }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Jadwal Kelas</h5>
                    @if($subject->classes->isEmpty())
                        <p class="text-muted">Belum ada jadwal kelas</p>
                    @else
                        <div class="list-group">
                            @foreach($subject->classes as $class)
                                <div class="list-group-item">
                                    <div class="d-flex flex-column flex-md-row w-100 justify-content-between align-items-start align-items-md-center gap-2">
                                        <div>
                                            <h6 class="mb-1">{{ $class->name }}</h6>
                                            <small class="text-muted d-block">
                                                {{ \Carbon\Carbon::parse($class->pivot->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($class->pivot->end_time)->format('H:i') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-primary">{{ $class->pivot->day }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
