<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Many Auth App
                </div>
                <p>Clique em um dos provedores abaixo e faça login na aplicação</p>

                <div class="links">
                    <a target="__blank" href="https://github.com/login/oauth/authorize?client_id={{env('GITHUB_OAUTH_ID')}}&redirect_uri={{env('GITHUB_REDIRECT_URI')}}&scopes={{env('GITHUB_OAUTH_SCOPES')}}">Github</a>
                    <a target="__blank" href="https://id.twitch.tv/oauth2/authorize?client_id={{env('TWITCH_OAUTH_ID')}}&redirect_uri={{env('TWITCH_REDIRECT_URI')}}&response_type=code&scope={{env('TWITCH_OAUTH_SCOPES')}}">Twitch</a>
                    <a target="__blank" href="https://accounts.spotify.com/authorize?client_id={{env('SPOTIFY_OAUTH_ID')}}&redirect_uri={{env('SPOTIFY_REDIRECT_URI')}}&response_type=code&scope={{env('SPOTIFY_OAUTH_SCOPES')}}">Spotify</a>
                    @if(Auth::check())
                        <a href="/auth/logout">Sair</a>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
