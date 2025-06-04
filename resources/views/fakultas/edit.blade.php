@extends('layouts.default')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="mb-4">Edit Fakultas</h1>

    <form action="{{ route('fakultas.update', $fakultas) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Fakultas</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $fakultas->nama) }}" required placeholder="Masukkan nama fakultas">
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('fakultas.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection
