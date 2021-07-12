<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('laratalk.name') }}</title>

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css', 'vendor/laratalk') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.2.0/css/all.min.css">

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Karla|Merriweather:400,700">
</head>
<body class="overflow-hidden">

    <div id="laratalk">
        <router-view></router-view>
    </div>

    <script>
        window.Laratalk = @json($scripts);
    </script>
    
    <script type="text/javascript" src="{{ mix('js/app.js', 'vendor/laratalk') }}"></script>
</body>
</html>