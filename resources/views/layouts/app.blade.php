<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <article class="message is-info" style="position: fixed;width: 100%;z-index: 9999;" v-show="ctsf == 1 || ts == 1 || cr == 1 || ct == 1">
            <div class="message-body">
                <div v-show="ctsf == 1">
                    <p><b>New Student:</b> Enter the total transferable credit amount applied to selected program upon entry.</p>
                    <p><b>Existing Student:</b> Enter the total amount of credit hours completed at Maryville up-to date, plus the total transferable credit amount applied to selected program upon entry.</p>
                </div>
                <div v-show="ts == 1">
                    <p><b>Standard:</b> Half-time pace in first term (6 cr), 3/4 Time pace thereafter (9 cr)</p>
                    <p><b>Accelerated:</b> 3/4 Time pace in first term (9 cr), Full-time pace thereafter (12 cr)</p>
                    <p><b>*Note*</b> Credits can be customized on next screen</p>
                </div>
                <div v-show="cr == 1">
                    <p>The total amount of credit hours the student has left to complete selected program.</p>
                </div>
                <div v-show="ct == 1">
                    <p>Section will auto-populate based on the track speed selected. Fields can be customized.</p>
                </div>
            </div>
        </article>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" role="navigation" aria-label="main navigation">
            <div class="container"><div class="row justify-content-center nav-hack"><div class="col-md-10">

                <div class="navbar-brand nav-name-pos-edit">
                    <a href="{{ url('/home') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto logo-hack">
                            <div class="logo" v-if="activeSchool == 1">
                                <img src="/images/mvu.png">
                            </div>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>

            </div></div></div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>
