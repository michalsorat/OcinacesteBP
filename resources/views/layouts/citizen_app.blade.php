<!DOCTYPE html>
<html lang="sk">
<head>
    @include('partials.head')
</head>

<body>
<header>
    @if(\Illuminate\Support\Facades\Request::is('/'))
        @include('partials.citizen.welcomePage_nav')
    @else
        @include('partials.citizen.allProblems_nav')
    @endif
    @guest
        @include('auth.login')
        @include('auth.register')
    @else
        @include('partials.settings')
        @include('partials.myAccountDetails')
    @endguest
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

<!-- Bootstrap core JavaScript -->
{{--@include('custom_layout.partials.footer-scripts')--}}

{{--<script>--}}
{{--    $(document).ready(function() {--}}
{{--        var mainHeight = $('.footer-top main').height() - $('.footer-top footer').height();--}}
{{--        $('.footer-top main').height(mainHeight).css('min-height', '100%');--}}
{{--    })--}}
{{--</script>--}}
{{--        <script async--}}
{{--            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFM1--RiO7MvE1qixa1jYWpWkau9YcJRg&libraries=places&callback=initAutocomplete">--}}
{{--        </script>--}}
</body>
</html>
