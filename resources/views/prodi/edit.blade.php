@extends('layouts.default')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="mb-4">Edit Prodi</h1>

    <form action="{{ route('tu.prodi.update', $prodi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Prodi</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $prodi->nama) }}" required placeholder="Masukkan nama prodi">
        </div>

        <div class="mb-3">
            <label for="fakultas_id" class="form-label">Fakultas</label>
            <select name="fakultas_id" id="fakultas_id" class="form-select" required>
                @foreach($fakultas as $f)
                    <option value="{{ $f->id }}" {{ old('fakultas_id', $prodi->fakultas_id) == $f->id ? 'selected' : '' }}>
                        {{ $f->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('tu.prodi.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection
