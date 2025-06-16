@extends('layouts.default') {{-- pastikan layout ini ada --}}
@section('content')
    <div class="container mt-4">

        <h4 class="mb-4">Manajemen File Approval</h4>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Form Tambah/Edit --}}
        <form action="{{ route('file-approvals.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pengajuan</label>
                    <select name="id_pengajuan" class="form-select" required>
                        @foreach ($pengajuans as $p)
                            <option value="{{ $p->id }}">{{ $p->id }} - {{ $p->nama ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">File Surat</label>
                    <input type="file" name="file_surat" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Tambah</button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <table class="table table-bordered table-striped" id="file-approvals-table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Pengajuan</th>
                    <th>Nomor Surat</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>


        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $fileApprovals->links() }}
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#file-approvals-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('file-approvals.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pengajuan',
                        name: 'pengajuan'
                    },
                    {
                        data: 'nomor_surat',
                        name: 'nomor_surat'
                    },
                    {
                        data: 'file_surat',
                        name: 'file_surat',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
