@extends('layouts.default')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="mb-4">Tambah Fakultas</h1>

    <form action="{{ route('fakultas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Fakultas</label>
            <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukkan nama fakultas">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('fakultas.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection
