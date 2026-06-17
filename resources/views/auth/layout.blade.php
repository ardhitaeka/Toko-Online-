<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - FashionStore</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-pink-50 via-white to-purple-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6">
        @yield('content')
    </div>
</body>
</html>
