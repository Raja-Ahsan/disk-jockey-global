<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        <!-- Styles / Scripts -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
            @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>