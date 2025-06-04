@extends('layouts.default')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="mb-4">Tambah Prodi</h1>

    <form action="{{ route('prodi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Prodi</label>
            <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukkan nama prodi">
        </div>

        <div class="mb-3">
            <label for="fakultas_id" class="form-label">Fakultas</label>
            <select name="fakultas_id" id="fakultas_id" class="form-select" required>
                <option value="" disabled selected>-- Pilih Fakultas --</option>
                @foreach($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('prodi.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection
