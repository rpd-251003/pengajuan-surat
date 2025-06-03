<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Pengajuan Surat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Mantis is made using Bootstrap 5 design framework.">
    <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template">
    <meta name="author" content="CodedThemes">

    <link rel="icon" href="{{ asset('v1/dist/assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style-preset.css') }}">
</head>

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
                            <h3 class="mb-0"><b>Login</b></h3>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email Address" required autofocus value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember">
                                        Keep me sign in
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
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

    <!-- Scripts -->
    <script src="{{ asset('v1/dist/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
        change_box_container(false);
        layout_rtl_change(false);
        preset_change("preset-1");
        font_change("Public-Sans");
    </script>
</body>

</html>
