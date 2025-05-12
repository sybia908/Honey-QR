@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Tambah Mata Pelajaran</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('subjects.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Mata Pelajaran</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="credits" class="form-label">SKS</label>
                            <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                                id="credits" name="credits" value="{{ old('credits', 0) }}" min="0" required>
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                            </div>

                            <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="teachers" class="form-label">Guru Pengajar</label>
                            <select class="form-select @error('teachers') is-invalid @enderror" 
                                id="teachers" name="teachers[]" multiple required>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                        {{ in_array($teacher->id, old('teachers', [])) ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teachers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="class-schedules">
                            <div class="mb-3">
                                <label class="form-label">Jadwal Kelas</label>
                                <div class="schedule-item border rounded p-3 mb-2">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <select class="form-select" name="classes[]" required>
                                                <option value="">Pilih Kelas</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <select class="form-select" name="days[]" required>
                                                <option value="">Pilih Hari</option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jumat">Jumat</option>
                                                <option value="Sabtu">Sabtu</option>
                                            </select>
                                            <div class="form-text">Pilih hari jadwal pelajaran</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="time" class="form-control" 
                                                name="start_times[]" required>
                                            <div class="form-text">Waktu mulai pelajaran</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="time" class="form-control" 
                                                name="end_times[]" required 
                                                placeholder="Waktu Selesai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-schedule">
                                <i class="bi bi-plus-circle"></i> Tambah Jadwal
                            </button>
                        </div>
                    </div>
                </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('add-schedule').addEventListener('click', function() {
    const template = document.querySelector('.schedule-item').cloneNode(true);
    template.querySelectorAll('select, input').forEach(input => {
        input.value = '';
    });
    document.getElementById('class-schedules').insertBefore(
        template, 
        document.getElementById('add-schedule')
    );
});
</script>
@endpush
