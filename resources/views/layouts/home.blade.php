<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Alto Centro - @yield('title')</title>
        <meta name="description" content="@yield('description')" />
        <meta name="keywords" content="@yield('keywords')" />
        <link rel='stylesheet' type='text/css' href='{{url(0)}}/bootstrap/css/bootstrap.min.css' />
        <script type='text/javascript' src='{{url(0)}}/bootstrap/js/jquery.js'>
        </script>
        <script type='text/javascript' src='{{url(0)}}/bootstrap/js/bootstrap.min.js'>
        </script>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
