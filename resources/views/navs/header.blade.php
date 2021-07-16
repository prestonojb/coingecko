<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <a href="/" class="navbar-brand">CoinGecko</a>
        </ul>
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            @auth
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Welcome back, {{ auth()->user()->email }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</a>
                            </form>
                        </div>
                    </div>
                </li>
            @else
                <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                <a class="nav-link btn btn-primary" href="{{ route('register') }}">Register</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>
