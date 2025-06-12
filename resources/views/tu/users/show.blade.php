<div class="row">
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID</label>
                            <p class="form-control-plaintext">{{ $user->id }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <p class="form-control-plaintext">{{ $user->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Email</label>
                            <p class="form-control-plaintext">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Terverifikasi</span>
                                    <small class="text-muted d-block">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                                @else
                                    <span class="badge bg-warning">Belum Terverifikasi</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <p class="form-control-plaintext">
                                @php
                                    $badges = [
                                        'admin' => 'bg-primary',
                                        'mahasiswa' => 'bg-success',
                                        'dosen' => 'bg-info',
                                        'kaprodi' => 'bg-warning',
                                        'wadek1' => 'bg-secondary',
                                        'tu' => 'bg-dark'
                                    ];
                                    $class = $badges[$user->role] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $class }}">{{ ucfirst($user->role) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Identifikasi</label>
                            <p class="form-control-plaintext">{{ $user->nomor_identifikasi ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Dibuat</label>
                            <p class="form-control-plaintext">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Terakhir Diupdate</label>
                            <p class="form-control-plaintext">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-warning" onclick="editUser({{ $user->id }})">
        <i class="ti ti-edit me-2"></i>Edit
    </button>
</div>
