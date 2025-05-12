@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">QR Code</h5>
                </div>

                <div class="card-body text-center">
                    <div class="mb-4">
                        {!! $qrImage !!}
                    </div>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Kode:</strong> {{ $qrcode->code }}</p>
                        <p class="mb-1"><strong>Dibuat oleh:</strong> {{ $qrcode->creator->name }}</p>
                        <p class="mb-1"><strong>Kelas:</strong> {{ $qrcode->class->name }}</p>
                        <p class="mb-1"><strong>Mata Pelajaran:</strong> {{ $qrcode->subject->name }} ({{ $qrcode->subject->code }})</p>
                        <p class="mb-0"><strong>Valid sampai:</strong> {{ $qrcode->valid_until->format('d/m/Y H:i') }}</p>
                                    @if ($qrcode->valid_until->isFuture())
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Kadaluarsa</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('qrcodes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <button class="btn btn-success" onclick="window.print()">
                                <i class="bi bi-printer"></i> Cetak
                            </button>
                            <form action="{{ route('qrcodes.destroy', $qrcode) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus QR Code ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
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
