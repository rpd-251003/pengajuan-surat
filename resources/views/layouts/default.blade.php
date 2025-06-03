<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Referral</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Raihan is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Raihan, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="Raihan Permadi">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('v1/dist/assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('v1/dist/assets/css/style-preset.css') }}">

    @stack('scripts')
</head>

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
                <a href="../" class="b-brand text-primary">
                    <!-- ========   Change your logo from here   ============ -->
                    <h4>TradingCamp</h4>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="../" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-home-2"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data Referral</label>
                        <i class="ti ti-link"></i>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('data.crypto') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-currency-bitcoin"></i></span>
                            <span class="pc-mtext">Data Crypto</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('data.forex') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-currency-dollar"></i></span>
                            <span class="pc-mtext">Data Forex</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data Master</label>
                        <i class="ti ti-database"></i>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('log-activities.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-activity"></i></span>
                            <span class="pc-mtext">Log Activity</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('users.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Data User</span>
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
                            <img src="{{ asset('v1/dist/assets/images/user/avatar-2.jpg') }}" alt="user-image"
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
                    <p class="m-0">TradingCamp &#9829; crafted by Team <a href="#"
                            target="_blank">R-COde</a>.</p>
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
</body>

</html>
