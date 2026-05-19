<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProTasker')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(135deg,#4e73df,#224abe);
            min-height: 100vh;
            margin: 0;
        }

        .auth-wrapper{
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .logo-header{
            max-width: 220px;
            width: 100%;
            height: auto;
            margin-bottom: 25px;
        }

        @yield('styles')
    </style>
</head>
<body>

    <div class="auth-wrapper">
        

        @yield('content')
    </div>

</body>
</html>