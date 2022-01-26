<!doctype html>
<html lang="sk">
<html manifest="MANIFEST">

<head>
    @include('partials.head')
</head>

<body>
@include('custom_layout.partials.admin.admin_nav')


<main>
    @yield('content')
</main>


<!-- Bootstrap core JavaScript -->
@include('custom_layout.partials.footer-scripts')
</body>
</html>
