<!DOCTYPE html>
<html lang="id">
<!-- [Head] start -->

<head>
    <title>Register | Pengajuan Surat</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistem Informasi Pengajuan Surat - Universitas Darma Persada">
    <meta name="keywords" content="Register, Surat, Mahasiswa, Universitas Darma Persada">
    <meta name="author" content="Universitas Darma Persada">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('v1/dist/assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style-preset.css') }}">
</head>
<!-- [Head] end -->

<!-- [Body] Start -->
<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header text-center">
                    <h4>Pengajuan Surat Kampus - Universitas Darma Persada</h4>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Daftar Akun Baru</b></h3>
                        </div>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required autofocus value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" name="ni" class="form-control @error('ni') is-invalid @enderror" required value="{{ old('ni') }}">
                                @error('ni')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Kata Sandi</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>

                            <div class="mt-3 text-center">
                                <a href="{{ route('login') }}" class="text-muted text-decoration-none">Sudah punya akun? Masuk</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="auth-footer row">
                    <div class="col my-1 text-center">
                        <p class="m-0">Copyright © <a href="#">Universitas Darma Persada</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('v1/dist/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/feather.min.js') }}"></script>
    <script>
        layout_change('light');
        change_box_container('false');
        layout_rtl_change('false');
        preset_change("preset-1");
        font_change("Public-Sans");
    </script>
</body>
<!-- [Body] end -->

</html>
