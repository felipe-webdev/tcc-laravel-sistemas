<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('meta-view')
    <link href="{{ asset('img/fav.png') }}" rel="icon" type="image/x-icon"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css-view')
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @yield('title-view')
  </head>

  <body class="bg-dark-blue-tint">
    @yield('vue-view')
    @yield('js-view')
  </body>
</html>