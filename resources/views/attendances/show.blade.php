@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detail Absensi</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="200">Tanggal</th>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>NIS</th>
                                <td>{{ $attendance->user->student->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $attendance->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>{{ $attendance->user->student->class->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jam Masuk</th>
                                <td>{{ $attendance->time_in }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi Masuk</th>
                                <td>
                                    <a href="https://www.google.com/maps?q={{ $attendance->latitude_in }},{{ $attendance->longitude_in }}" target="_blank">
                                        {{ $attendance->latitude_in }}, {{ $attendance->longitude_in }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Jam Keluar</th>
                                <td>{{ $attendance->time_out ?? '-' }}</td>
                            </tr>
                            @if($attendance->time_out)
                            <tr>
                                <th>Lokasi Keluar</th>
                                <td>
                                    <a href="https://www.google.com/maps?q={{ $attendance->latitude_out }},{{ $attendance->longitude_out }}" target="_blank">
                                        {{ $attendance->latitude_out }}, {{ $attendance->longitude_out }}
                                    </a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($attendance->status === 'on_time')
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    @else
                                        <span class="badge bg-warning">Terlambat</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>QR Code</th>
                                <td>{{ $attendance->qrcode->code }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button class="btn btn-success" onclick="window.print()">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
        }
        .btn {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
