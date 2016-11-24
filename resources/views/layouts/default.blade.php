<!DOCTYPE html>
<html>
<head>
    <title>Christmastop 100</title>

    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
</head>
<body>
<div id="snow"></div><div id="santa"></div>

<header>
    @yield('header')
</header>

<main>
    @yield('content')
</main>

<footer>
    @yield('footer')
</footer>

<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
</body>
</html>
