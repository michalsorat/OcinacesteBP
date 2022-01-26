<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <h1 class="main_header"><a href="{{ route('welcome') }}">Oči na ceste</a></h1>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-md-column" id="navbarCollapse">
        <ul class="navbar-nav ml-auto welcomePage-nav mr-3">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('welcome') }}">Mapa<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('problem.index') }}">Zoznam hlásení</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('download') }}">Mobilná aplikácia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">O projekte</a>
            </li>
            @guest
                <li class="nav-item dropdown active">
                    <button class="nav-link dropdown-toggle btn btn-success" id="welcome-page-login-link" data-toggle="dropdown">Prihlásiť sa</button>
                    @include('auth.login')
{{--                    <div class="dropdown-menu dropdown-menu-right p-3" role="menu" style="width: 300px">--}}
{{--                        <form class="loginForm" method="POST" action="{{ route('login') }}">--}}
{{--                            @csrf--}}

{{--                            <div class="form-group mt-2">--}}
{{--                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Emailová adresa" value="" required>--}}

{{--                                @error('email')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}

{{--                            <div class="form-group">--}}
{{--                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Heslo" required>--}}

{{--                                @error('password')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}

{{--                            <div class="form-group">--}}
{{--                                <input type="submit" name="login-submit" id="login-submit" class="form-control btn btn-success" value="Prihlásiť sa">--}}
{{--                            </div>--}}

{{--                            <div class="form-group mb-0 text-center">--}}
{{--                                <span class="outer-link">Ešte nemáte účet? <a href="{{ route('register') }}">Zaregistrujte sa</a></span>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
                </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Odhlásenie') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
        <form class="form-inline ml-auto mr-4">
            <div class="input-group">
                <input id="search-input" class="typeahead form-control" type="search"
                       placeholder="Vyhľadaj hlásenie podľa adresy" autocomplete="off" size="30">
                <span class="input-group-append">
                                <button id="search_btn" class="btn btn-outline-success" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                        </span>
            </div>
        </form>
    </div>

</nav>

<script type="text/javascript">
    var path = "{{ route('autocomplete') }}";
    $('#search-input').typeahead({
        source: function (query, process) {
            return $.get(path, {query: query}, function (data) {
                return process(data);
            });
        }
    });
    const search_button = document.getElementById("search_btn");

    search_button.addEventListener("click", function () {
        findProblemWithAddress(document.getElementById('search-input').value);
    });
</script>

