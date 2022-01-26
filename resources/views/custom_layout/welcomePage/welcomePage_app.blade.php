<!DOCTYPE html>
<html lang="sk">
    <head>
        @include('partials.head')
    </head>

{{--    <body class="{{ Request::is('/') ? 'footer-top' : '' }}">--}}
{{--    @include('custom_layout.partials.welcomePage.welcomePage_nav')--}}

    <body>
        <header>
            @if(\Illuminate\Support\Facades\Request::is('/'))
                @include('partials.citizen.welcomePage_nav')
            @else
                @include('partials.citizen.allProblems_nav')
            @endif
            @include('auth.login')
            @include('auth.register')
        </header>

        <main>
                @yield('content')
        </main>
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
