<!DOCTYPE html>
<html lang="en">
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

    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap.min.css") }}" id="app-style">
    <link rel="stylesheet" href="{{ asset("assets/css/app.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/icons.min.css") }}">

    <link rel="stylesheet" href="{{ asset("assets/css/theme.css") }}">
</head>
<body class="auth-bg">

    @yield("content")

    <div class="auth-overlay"></div>
    
    <script src="{{ asset("assets/js/vendor.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/authentication.init.js") }}"></script>

    @yield("scripts")
</body>
</html>