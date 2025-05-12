@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar QR Code</h5>
                    <a href="{{ route('qrcodes.create') }}" class="btn btn-primary">Buat QR Code</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Berlaku Sampai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($qrcodes as $qrcode)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $qrcode->code }}</td>
                                        <td>{{ $qrcode->class ? $qrcode->class->name : 'Tidak ada' }}</td>
                                        <td>{{ $qrcode->subject ? $qrcode->subject->name . ' (' . $qrcode->subject->code . ')' : 'Tidak ada' }}</td>
                                        <td>{{ $qrcode->creator->name }}</td>
                                        <td>{{ $qrcode->valid_until->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($qrcode->valid_until->isFuture())
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Kadaluarsa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('qrcodes.show', $qrcode) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <form action="{{ route('qrcodes.destroy', $qrcode) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus QR Code ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $qrcodes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
