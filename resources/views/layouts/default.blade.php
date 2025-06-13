<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SI Pengajuan Surat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Sistem Pengajuan Surat Universitas Darma Persada untuk memudahkan proses administrasi dan pengelolaan surat bagi civitas akademika.">
    <meta name="keywords"
        content="Universitas Darma Persada, Sistem Pengajuan Surat, Pengajuan Surat Online, Administrasi Universitas, Surat Resmi Kampus">
    <meta name="author" content="Universitas Darma Persada">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="https://lpm1.unsada.ac.id/wp-content/uploads/2021/07/logo-unsada-asli-300x300-1.png"
        type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style-preset.css') }}">


    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

@stack('styles')

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('dashboard') }}" class="b-brand mt-3 text-primary">
                    <!-- ========   Change your logo from here   ============ -->
                    <h5> <img src="https://lpm1.unsada.ac.id/wp-content/uploads/2021/07/logo-unsada-asli-300x300-1.png"
                            width="30" alt=""> SPS</h5>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">

                    @php
                        $excludedRoles = ['mahasiswa', 'dosen'];
                    @endphp

                    @if (!in_array(Auth::user()->role, $excludedRoles))
                        <li class="pc-item">
                            <a href="{{ route('dashboard') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-home-2"></i></span>
                                <span class="pc-mtext">Dashboard</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.pengajuan.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                                <span class="pc-mtext">List Pengajuan</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'dosen')
                        <li class="pc-item">
                            <a href="{{ route('admin.pengajuan.dosen') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                                <span class="pc-mtext">List Pengajuan</span>
                            </a>
                        </li>
                    @endif



                    @if (Auth::user()->role == 'tu')
                        <li class="pc-item pc-caption">
                            <label>Data Master</label>
                            <i class="ti ti-database"></i>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('fakultas.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-building"></i></span>
                                <span class="pc-mtext">Data Fakultas</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('prodi.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-book"></i></span>
                                <span class="pc-mtext">Data Prodi</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('mahasiswa.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-database"></i></span>
                                <span class="pc-mtext">Data Mahasiswa</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('kaprodi-tahunan.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-database"></i></span>
                                <span class="pc-mtext">Atur Kaprodi</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('dosen-pa-tahunan.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-database"></i></span>
                                <span class="pc-mtext">Atur Dosen PA</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('jenis-surat.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                                <span class="pc-mtext">Jenis Surat</span>
                            </a>
                        </li>

                        <li class="pc-item pc-caption">
                            <label>Data Master</label>
                            <i class="ti ti-database"></i>
                        </li>
                        {{-- <li class="pc-item">
                        <a href="{{ route('log-activities.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-activity"></i></span>
                            <span class="pc-mtext">Log Activity</span>
                        </a>
                    </li> --}}


                        <li class="pc-item">
                            <a href="{{ route('users.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-users"></i></span>
                                <span class="pc-mtext">Manajemen Users</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->role == 'mahasiswa')
                        <li class="pc-item">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-home-2"></i></span>
                                <span class="pc-mtext">Dashboard</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('pengajuan_surat.create') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-mail-forward"></i></span>
                                <span class="pc-mtext">Pengajuan Surat</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('pengajuan_surat.history') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-history"></i></span>
                                <span class="pc-mtext">History Pengajuan</span>
                            </a>
                        </li>
                    @endif


                    <li class="pc-item">
                        <a href="{{ route('profile.edit') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Profile</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="pc-link"
                                style="background: none; border: none; cursor: pointer;">
                                <span class="pc-micon"><i class="ti ti-logout"></i></span>
                                <span class="pc-mtext">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <!-- ======= Menu collapse Icon ===== -->
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                            aria-expanded="false">
                            <img src="{{ asset('v1/dist/assets/images/img-navbar-card.png') }}" alt="user-image"
                                class="user-avtar">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">UNSADA - Sistem Pengajuan Surat.</p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Required Js -->
    <script src="{{ asset('v1/dist/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('v1/dist/assets/js/plugins/feather.min.js') }}"></script>
    <script>
        layout _change('light');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change("preset-1");
    </script>
    <script>
        font_change("Public-Sans");
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')


</body>

</html>
