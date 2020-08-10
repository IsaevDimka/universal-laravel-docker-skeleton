@php
$config = [
    'environment' => config('app.env'),
    'timezone'    => config('app.timezone'),
    'debug'       => config('app.debug'),
    'version'     => Version::format('compact'),
    'appName'     => config('app.name'),
    'locale'      => app()->getLocale(),
    'locales'     => config('app.locales'),
    'githubAuth'  => config('services.github.client_id'),
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ mix('dist/css/app.css') }}" type="text/css" rel="stylesheet" />
</head>
<body>

<div id="app">
    <app></app>
</div>

{{-- Global configuration object --}}
<script>
    window.config = @json($config);
</script>

{{-- Load the application scripts --}}
<script src=/static/tinymce4.7.5/tinymce.min.js></script>
<script src="{{ mix('dist/js/vendor.js') }}"></script>
<script src="{{ mix('dist/js/manifest.js') }}"></script>
<script src="{{ mix('dist/js/app.js') }}"></script>
</body>
</html>