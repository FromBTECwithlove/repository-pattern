<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | TINHTV - CURD - REPOSITORY - PATTERN</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[rgb(0,0,0,0.1)]">
<main>
    <div class="w-max mx-auto">
        <div class="relative w-full h-full flex items-center justify-center flex-col gap-5 py-10">
            @yield('content')
        </div>
    </div>
</main>

<script src="{{ asset('vendor/jquery/jquery-3.7.0.min.js') }}"></script>

@stack('script')
</body>
</html>
