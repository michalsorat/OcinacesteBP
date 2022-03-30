<!DOCTYPE html>
<html lang="sk">
    <head>
        @include('partials.head')
    </head>

    <body>
        <header>
            @if(\Illuminate\Support\Facades\Request::is('/'))
                @include('partials.citizen.citizen_mapNav')
            @else
                @include('partials.citizen.citizen_nav')
            @endif
            @guest
                @include('auth.login')
                @include('auth.register')
            @endguest
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
