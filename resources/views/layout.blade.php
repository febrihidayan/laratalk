<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('laratalk.name') }}</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Karla|Merriweather:400,700">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css', 'vendor/laratalk') }}">
</head>
<body>

    <div id="laratalk"></div>

    <script>
        window.laratalk = @json($scripts);
    </script>
    
    <script type="text/javascript" src="{{ mix('js/app.js', 'vendor/laratalk') }}"></script>
</body>
</html>