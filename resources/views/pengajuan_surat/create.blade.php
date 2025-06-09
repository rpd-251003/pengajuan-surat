@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card card-body">

            <h3>Pengajuan Surat</h3>

            <form id="pengajuanSuratForm" method="POST" action="{{ route('pengajuan_surat.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="jenis_surat" class="form-label">Jenis Surat</label>
                    <select name="jenis_surat" id="jenis_surat" class="form-control">
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach ($jenisSurats as $surat)
                            <option value="{{ $surat->id }}">{{ $surat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="deskripsi_surat" class="mt-3"></div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan (isi sesuai tata cara di atas)</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Ajukan Surat</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#jenis_surat').on('change', function() {
            const jenisSuratId = $(this).val();
            if (jenisSuratId) {
                $.ajax({
                    url: '{{ route('pengajuan_surat.deskripsi') }}',
                    type: 'GET',
                    data: {
                        jenis_surat: jenisSuratId
                    },
                    success: function(response) {
                        // Replace newlines with <br>
                        const formattedDeskripsi = response.deskripsi.replace(/\n/g, '<br>');
                        $('#deskripsi_surat').html(
                            `<div class="alert alert-primary">${formattedDeskripsi}</div>`);
                    }
                });
            } else {
                $('#deskripsi_surat').html('');
            }
        });
    </script>
@endpush
