<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <h1 class="main_header"><a href="{{ route('welcome') }}">Oči na ceste VPS</a></h1>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mr-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('welcome') }}">Mapa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('problem.index') }}">Zoznam problémov</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('download') }}">Mobilná aplikácia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">O projekte</a>
            </li>
            @guest
                <li class="nav-item dropdown active">
                    <button class="nav-link btn btn-success" data-toggle="modal" data-target="#loginModal">Prihlásiť sa</button>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                        <a class="dropdown-item" id="userDetails" href="{{Auth::user()->id}}">Môj účet</a>

                        <button class="dropdown-item" data-toggle="modal"
                                data-target="#edit-modal-{{Auth::user()->id}}">Nastavenia
                        </button>

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
    </div>
</nav>
