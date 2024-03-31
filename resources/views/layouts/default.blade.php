<!DOCTYPE html>
<html lang="en" data-layout="horizontal">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield("title") | Deped Supply Management System
    </title>
    <link rel="shortcut icon" href="{{ asset("assets/images/deped.png") }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.css") }}">
    @yield("links")

    <script src="{{ asset("assets/js/head.min.js") }}"></script>

    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap.min.css") }}" id="app-style">
    <link rel="stylesheet" href="{{ asset("assets/css/app.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/icons.min.css") }}">
    
    <link rel="stylesheet" href="{{ asset("assets/css/theme.css") }}">

    <style>
        html[data-layout=horizontal] .content {
            min-height: calc(100vh - var(--ct-horizontal-menu-height) - 15px)
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="preloader">
            <div id="status">
                <div class="spinner">Loading...</div>
            </div>
        </div>

        <div class="content-page">
            <div class="navbar-custom">
                <div class="topbar">
                    <div class="topbar-menu d-flex align-items-center gap-1">
                        <div class="logo-box">
                            <a href="{{ route("employee.home") }}" class="logo-light">
                                <img src="{{ asset("assets/images/deped.png") }}" alt="logo" height="40" />
                            </a>
                    
                            <a href="{{ route("employee.home") }}" class="logo-dark">
                                <img src="{{ asset("assets/images/deped.png") }}" alt="dark logo" height="40" />
                            </a>
                        </div>
                    </div>

                    <ul class="topbar-menu d-flex align-items-center">
                        <li class="d-none d-sm-inline-block">
                            <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                                <i class="ri-moon-line font-22"></i>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" 
                                data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{ asset("assets/images/employee.jpg") }}" alt="user-image" class="rounded-circle" />
                                <span class="ms-1 d-none d-md-inline-block">
                                    {{ auth()->guard("employee")->user()->full_name }} <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>
            
                                <a href="{{ route("employee.profile.index") }}" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>My Profile</span>
                                </a>

                                <a href="{{ route("employee.requests.index") }}" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>My Request</span>
                                </a>

                                <div class="dropdown-divider"></div>
            
                                <a href="{{ route("employee.logout") }}" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>
            
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="content" id="app">
                <div class="container px-0">
                    @yield("content")
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div><script>document.write(new Date().getFullYear())</script> © Deped Supply Management System, Alright Reserved</div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end footer-links">
                                <a href="https://www.facebook.com/clyde.arellano.31">Developer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <script src="{{ asset("assets/js/vendor.min.js") }}"></script>
    <script src="{{ asset("assets/js/app.min.js") }}"></script>

    <script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
    @include("toastr")
    @yield("scripts")

</body>
</html>