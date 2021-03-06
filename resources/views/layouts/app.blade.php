<!DOCTYPE html>
<html lang="sk">
<head>
    @include('partials.head')
</head>

<body>
<header>
{{--    citizen--}}
    @if(Auth::user() == null || Auth::user()->rola_id == 1)
        @if(\Illuminate\Support\Facades\Request::is('/'))
            @include('partials.citizen.citizen_mapNav')
        @else
            @include('partials.citizen.citizen_nav')
        @endif
{{--    admin--}}
    @elseif(Auth::user()->rola_id == 3)
        @if(\Illuminate\Support\Facades\Request::is('/'))
            @include('partials.admin.nav.admin_mapNav')
        @else
            @include('partials.admin.nav.admin_nav')
        @endif
{{--    worker--}}
    @elseif(Auth::user()->rola_id == 4)
        @if(\Illuminate\Support\Facades\Request::is('/'))
            @include('partials.worker.nav.worker_mapNav')
        @else
            @include('partials.worker.nav.worker_nav')
        @endif
{{--    manager--}}
    @elseif(Auth::user()->rola_id == 5)
        @if(\Illuminate\Support\Facades\Request::is('/'))
            @include('partials.manager.nav.manager_mapNav')
        @else
            @include('partials.manager.nav.manager_nav')
        @endif
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
</main>
{{--@include('partials.footer')--}}

</body>
</html>
