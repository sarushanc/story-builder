<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styles -->
        <style>
            body {
                background: url('{{ asset('images/background.png') }}') no-repeat center center fixed;
                background-size: cover;
                color: white; /* Ensure the text stands out */
            }

            .content-box {
                background-color: rgba(255, 255, 255, 0.85); /* Light background for readability */
                color: black; /* Default text color */
            }

            .logo-container {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            <!-- Main Content -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 content-box shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
