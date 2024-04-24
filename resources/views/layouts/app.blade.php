<!DOCTYPE html>
<html lang="en" data-topbar-color="dark" data-sidenav-side="condensed">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield("title") | Deped Supply Management System
    </title>
    <link rel="shortcut icon" href="{{ asset("assets/images/deped.png") }}" type="image/x-icon">

    @yield("links")

    <script src="{{ asset("assets/js/head.min.js") }}"></script>

    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap.min.css") }}" id="app-style">
    <link rel="stylesheet" href="{{ asset("assets/css/app.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/icons.min.css") }}">

</head>
<body>

    <div id="wrapper">
        <div id="preloader">
            <div id="status">
                <div class="spinner">Loading...</div>
            </div>
        </div>
        <div class="app-menu">
            <div class="logo-box">
                <a href="{{ route("admin.dashboard") }}" class="logo-light">
                    <img src="{{ asset("assets/images/deped.png") }}" alt="logo" height="30" />
                </a>
        
                <a href="{{ route("admin.dashboard") }}" class="logo-dark">
                    <img src="{{ asset("assets/images/deped.png") }}" alt="dark logo" height="30" />
                </a>
            </div>

            <div class="scrollbar">
                <ul class="menu">
                    <li class="menu-title">Home</li>
                    <li class="menu-item">
                        <a href="{{ route("admin.dashboard") }}" class="menu-link">
                            <span class="menu-icon"><i class="mdi mdi-view-dashboard"></i></span>
                            <span class="menu-text"> Dashboard </span>
                        </a>
                    </li>
                    <li class="menu-title">Informations</li>
                    <li class="menu-item">
                        <a href="{{ route("admin.positions.index") }}" class="menu-link">
                            <span class="menu-icon"><i class="mdi mdi-offer"></i></span>
                            <span class="menu-text"> Position </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route("admin.offices.index") }}" class="menu-link">
                            <span class="menu-icon"><i class="mdi mdi-office-building-marker"></i></span>
                            <span class="menu-text"> Office </span>
                        </a>
                    </li>
                    <li class="menu-title">User</li>
                    <li class="menu-item">
                        <a href="{{ route("admin.employees.index") }}" class="menu-link">
                            <span class="menu-icon"><i class="mdi mdi-account-arrow-left"></i></span>
                            <span class="menu-text"> Employees </span>
                        </a>
                    </li>
                    <li class="menu-title">Inventory</li>
                    <li class="menu-item">
                        <a href="{{ route("admin.items.index") }}" class="menu-link">
                            <span class="menu-icon"><i class="mdi mdi-box-shadow"></i></span>
                            <span class="menu-text"> Items </span>
                        </a>
                    </li>
                    <li class="menu-title">Transaction</li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
													<span class="menu-icon"><i class="mdi mdi-clipboard-account"></i></span>
													<span class="menu-text"> Requests </span>
												</a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="content-page">
            <div class="navbar-custom">
                <div class="topbar">
                    <div class="topbar-menu d-flex align-items-center gap-1">
                        <button class="button-toggle-menu">
                            <i class="mdi mdi-menu"></i>
                        </button>
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
                                <img src="{{ asset("assets/images/avatar.jpg") }}" alt="user-image" class="rounded-circle" />
                                <span class="ms-1 d-none d-md-inline-block">
                                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>
            
                                <a href="{{ route("admin.profile") }}" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>My Profile</span>
                                </a>
            
                                <a href="{{ route("admin.logout") }}" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>
            
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="content" id="app">
                <div class="container-fluid">
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

    @yield("scripts")
</body>
</html>