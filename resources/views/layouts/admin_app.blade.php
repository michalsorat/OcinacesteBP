<!DOCTYPE html>
<html lang="sk">
<head>
    @include('partials.head')
</head>

<body>
    <header>
        @include('partials.admin.admin_nav')
        @include('partials.settings')
        @include('partials.myAccountDetails')
    </header>

    <main>
        @yield('content')
    </main>

    <script>
        $(function () {
            var url = window.location.href;
            $(".navbar-nav .nav-link").each(function () {
                if (url == (this.href)) {
                    $(this).closest(".nav-link").addClass("active");
                    $(this).closest("nav-link").parent().parent().addClass("active");
                }
            });
        });
    </script>
</body>
</html>
