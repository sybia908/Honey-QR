@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Buat QR Code</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('qrcodes.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="class_id" class="form-label">Kelas</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" 
                                id="class_id" 
                                name="class_id" 
                                required>
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('class_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Mata Pelajaran</label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                id="subject_id" 
                                name="subject_id" 
                                required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valid_until" class="form-label">Valid Sampai</label>
                            <input type="datetime-local" 
                                class="form-control @error('valid_until') is-invalid @enderror" 
                                id="valid_until" 
                                name="valid_until" 
                                value="{{ old('valid_until') }}" 
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                required>
                            @error('valid_until')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i> QR Code akan aktif sesuai dengan jadwal pelajaran yang telah ditentukan untuk kelas dan mata pelajaran yang dipilih.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('qrcodes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-qr-code"></i> Generate QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
