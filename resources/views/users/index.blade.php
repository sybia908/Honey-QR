@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-sm-6 mb-3 mb-sm-0">
            <h2 class="h3 mb-0">Manajemen User</h2>
            <p class="text-muted mb-0">Kelola data pengguna sistem</p>
        </div>
        <div class="col-12 col-sm-6 d-flex justify-content-sm-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary w-100 w-sm-auto">
                <i class="bi bi-plus-circle me-1"></i> Tambah User
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" 
                                        alt="{{ $user->name }}" 
                                        class="rounded-circle shadow-sm"
                                        width="40" height="40"
                                        style="object-fit: cover;">
                                @else
                                    <div class="text-muted d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-info rounded-pill">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('users.toggle-active', $user) }}" 
                                    method="POST" 
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="btn btn-sm {{ $user->is_active ? 'btn-success' : 'btn-secondary' }} rounded-pill">
                                        <i class="bi {{ $user->is_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Action buttons">
                                    <a href="{{ route('users.show', $user) }}" 
                                        class="btn btn-info btn-sm action-btn"
                                        data-bs-toggle="tooltip"
                                        title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" 
                                        class="btn btn-warning btn-sm action-btn"
                                        data-bs-toggle="tooltip"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                        class="btn btn-danger btn-sm action-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $user->id }}"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade delete-modal" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>? Tindakan ini tidak dapat dibatalkan.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end p-3 border-top">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
